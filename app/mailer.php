<?php
/**
 * Küçük SMTP istemcisi (harici kütüphane yok).
 * Sunucuda PHP mail() devre dışı olduğu için bildirimler cPanel e-posta hesabı üzerinden SMTP ile gider.
 * Sunucu/kullanıcı/parola admin panelindeki "E-posta Ayarları" sayfasından girilir (settings tablosu).
 */

/** SMTP ayarları (panel → settings tablosu, yoksa config 'defaults') */
function smtp_config(): array
{
    return [
        'host' => setting('smtp_host'), 'port' => (int) setting('smtp_port'), 'secure' => setting('smtp_secure'),
        'user' => setting('smtp_user'), 'pass' => setting('smtp_pass'),
        'from' => setting('smtp_from') ?: setting('smtp_user'), 'from_name' => setting('smtp_from_name') ?: cfg('site_name'),
    ];
}

/** RFC 2047 başlık kodlaması (UTF-8) */
function mime_header(string $s): string
{
    return preg_match('/[^\x20-\x7E]/', $s) ? '=?UTF-8?B?' . base64_encode($s) . '?=' : $s;
}

/**
 * Düz metin e-posta gönder. Başarılıysa true.
 * Hata olursa error_log'a yazar, istisna fırlatmaz (form akışı bozulmaz).
 */
function smtp_send(string $to, string $subject, string $body, string $replyTo = '', string $replyName = ''): bool
{
    $c = smtp_config();
    $pass = $c['pass'];
    if ($c['host'] === '' || $c['user'] === '' || $pass === '') {
        $GLOBALS['__mail_error'] = 'SMTP yapılandırılmamış (Ayarlar → SMTP parolası boş)';
        error_log('[mailer] ' . $GLOBALS['__mail_error']);
        return false;
    }
    $secure = $c['secure'] ?: 'ssl';
    $remote = ($secure === 'ssl' ? 'ssl://' : 'tcp://') . $c['host'] . ':' . (int) $c['port'];
    $ctx = stream_context_create(['ssl' => ['verify_peer' => true, 'verify_peer_name' => true, 'SNI_enabled' => true]]);
    $fp = @stream_socket_client($remote, $errno, $errstr, 15, STREAM_CLIENT_CONNECT, $ctx);
    if (!$fp) {
        // Paylaşımlı sunucularda sertifika çoğu zaman sunucu adına (*.srvpanel.com vb.) verilir; localhost'a doğrulamasız düş
        $ctx = stream_context_create(['ssl' => ['verify_peer' => false, 'verify_peer_name' => false]]);
        $fp = @stream_socket_client(($secure === 'ssl' ? 'ssl://' : 'tcp://') . 'localhost:' . (int) $c['port'], $errno, $errstr, 15, STREAM_CLIENT_CONNECT, $ctx);
    }
    if (!$fp) {
        $GLOBALS['__mail_error'] = "Bağlantı kurulamadı: $errstr ($errno)";
        error_log('[mailer] ' . $GLOBALS['__mail_error']);
        return false;
    }
    stream_set_timeout($fp, 15);

    $read = function () use ($fp): string {
        $data = '';
        while (($line = fgets($fp, 515)) !== false) {
            $data .= $line;
            if (strlen($line) < 4 || $line[3] === ' ') break; // çok satırlı yanıtın son satırı
        }
        return $data;
    };
    // $label: günlüğe yazılacak ad. Kullanıcı adı/şifre/mesaj satırları ASLA olduğu gibi yazılmaz (base64 geri çözülebilir).
    $cmd = function (string $line, array $okCodes, ?string $label = null) use ($fp, $read): bool {
        if ($line !== '') fwrite($fp, $line . "\r\n");
        $resp = $read();
        $ok = in_array((int) substr($resp, 0, 3), $okCodes, true);
        if (!$ok) {
            $GLOBALS['__mail_error'] = ($label ?? ($line === '' ? 'bağlantı' : strtok($line, ' :'))) . ' → ' . trim($resp);
            error_log('[mailer] ' . $GLOBALS['__mail_error']);
        }
        return $ok;
    };

    $ehloHost = preg_replace('/:\d+$/', '', request_host());
    $ok = $cmd('', [220]) && $cmd('EHLO ' . $ehloHost, [250]);
    if ($ok && $secure === 'tls') {
        $ok = $cmd('STARTTLS', [220])
           && stream_socket_enable_crypto($fp, true, STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT | STREAM_CRYPTO_METHOD_TLSv1_3_CLIENT)
           && $cmd('EHLO ' . $ehloHost, [250]);
    }
    $ok = $ok && $cmd('AUTH LOGIN', [334]) && $cmd(base64_encode($c['user']), [334], 'kullanıcı adı') && $cmd(base64_encode($pass), [235], 'şifre');
    $ok = $ok && $cmd('MAIL FROM:<' . $c['from'] . '>', [250]) && $cmd('RCPT TO:<' . $to . '>', [250, 251]) && $cmd('DATA', [354]);
    if ($ok) {
        $h  = 'Date: ' . date('r') . "\r\n";
        $h .= 'From: ' . mime_header($c['from_name'] ?: 'Website') . ' <' . $c['from'] . ">\r\n";
        $h .= 'To: <' . $to . ">\r\n";
        if ($replyTo && filter_var($replyTo, FILTER_VALIDATE_EMAIL)) {
            $h .= 'Reply-To: ' . ($replyName ? mime_header($replyName) . ' ' : '') . '<' . $replyTo . ">\r\n";
        }
        $h .= 'Subject: ' . mime_header($subject) . "\r\n";
        $h .= 'Message-ID: <' . bin2hex(random_bytes(12)) . '@' . substr(strrchr($c['from'], '@'), 1) . ">\r\n";
        $h .= "MIME-Version: 1.0\r\nContent-Type: text/plain; charset=UTF-8\r\nContent-Transfer-Encoding: base64\r\n";
        $data = $h . "\r\n" . chunk_split(base64_encode($body)) . "\r\n.";
        $ok = $cmd($data, [250], 'mesaj gövdesi');
    }
    @fwrite($fp, "QUIT\r\n");
    fclose($fp);
    return $ok;
}
