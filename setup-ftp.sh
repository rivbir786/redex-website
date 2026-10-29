#!/usr/bin/env bash
# =============================================================
# BİR KEZ çalıştırın: FTP parolasını güvenli şekilde ~/.netrc'ye kaydeder.
# Parolayı siz girersiniz; dosya yalnızca sizin okuyabileceğiniz şekilde (chmod 600) saklanır.
# Kullanım:  bash setup-ftp.sh
# =============================================================
set -euo pipefail

HOST="deneme.redexglass.com"
LOGIN="deneme@redexglass.com"

if grep -q "machine $HOST" "$HOME/.netrc" 2>/dev/null; then
  echo "~/.netrc zaten $HOST için bir kayıt içeriyor."
  echo "Parolayı güncelleyecekseniz, ~/.netrc dosyasındaki ilgili satırları elle silip tekrar çalıştırın."
  exit 0
fi

read -r -s -p "FTP parolası ($LOGIN): " PW
echo
if [ -z "$PW" ]; then echo "Boş parola girildi, iptal edildi."; exit 1; fi

umask 077
printf 'machine %s\nlogin %s\npassword %s\n' "$HOST" "$LOGIN" "$PW" >> "$HOME/.netrc"
chmod 600 "$HOME/.netrc"
unset PW

echo "Tamam. Kimlik bilgisi ~/.netrc içine kaydedildi (sadece siz okuyabilirsiniz)."
echo "Artık her yüklemede sadece:  bash deploy.sh   —  parola sormaz."
