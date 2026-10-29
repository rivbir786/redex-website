#!/usr/bin/env bash
# =============================================================
# Redex Glass — deneme.redexglass.com'a FTP yükleme (otomatik)
# Kimlik bilgisi ~/.netrc dosyasından okunur; parola SORMAZ.
# (Bir kez ~/.netrc kurulumu için README-DEPLOY.txt'ye bakın.)
# Kullanım:  bash deploy.sh      (redex-site klasörünün içinde)
# =============================================================
set -euo pipefail

FTP_HOST="deneme.redexglass.com"
REMOTE_DIR="."     # Alt alan adının köküne düşmüyorsa: "/public_html/deneme" gibi değiştirin.

cd "$(dirname "$0")"
[ -f index.php ] || { echo "HATA: redex-site klasörünün içinde çalıştırın."; exit 1; }
command -v lftp >/dev/null 2>&1 || { echo "lftp yok -> macOS: brew install lftp | Ubuntu: sudo apt-get install lftp"; exit 1; }

if [ ! -f "$HOME/.netrc" ] || ! grep -q "deneme.redexglass.com" "$HOME/.netrc" 2>/dev/null; then
  echo "UYARI: ~/.netrc bulunamadı. Bir kez kurulum gerekir (README-DEPLOY.txt)."
fi

echo ">> Yükleniyor: ftp://$FTP_HOST -> $REMOTE_DIR"

# storage içindeki canlı veritabanı, admin şifresi ve CV'ler tekrar yüklemede KORUNUR.
lftp "$FTP_HOST" <<EOF
set ftp:ssl-allow true
set ssl:verify-certificate no
set net:timeout 20
set net:max-retries 2
set cmd:fail-exit yes
mirror -R --verbose --delete \
  --exclude storage/database.sqlite \
  --exclude storage/database.sqlite-wal \
  --exclude storage/database.sqlite-shm \
  --exclude storage/admin.php \
  --exclude storage/uploads/ \
  ./ "$REMOTE_DIR"
bye
EOF

echo ">> Bitti. Kontrol: https://deneme.redexglass.com"
