REDEX GLASS — OTOMATİK FTP YÜKLEME (deneme.redexglass.com)
===========================================================

Amaç: Siteyi tek komutla, parola yazmadan FTP'ye yüklemek.
Parolayı YALNIZCA bir kez, kendi bilgisayarınıza kaydedersiniz. Sonrası otomatik.

GEREKLİ ARAÇ: lftp
  macOS:   brew install lftp
  Ubuntu:  sudo apt-get install lftp

-----------------------------------------------------------
ADIM 1 — BİR KEZ: parolayı kaydet
-----------------------------------------------------------
redex-site klasörünün içinde, kendi Terminal'inizde:

    bash setup-ftp.sh

Parolanızı sorar (ekranda görünmez) ve ~/.netrc dosyasına güvenli (chmod 600)
şekilde yazar. Bu adımı yalnızca bir kez yaparsınız.

-----------------------------------------------------------
ADIM 2 — HER YÜKLEMEDE: tek komut
-----------------------------------------------------------
    bash deploy.sh

Parola sormaz; siteyi FTP'ye yükler. Canlı veritabanını, admin şifresini ve
yüklenen CV'leri KORUR (silmez).

Bunu Claude Code'a da yaptırabilirsiniz: "redex-site klasöründe deploy.sh'ı çalıştır"
demeniz yeterli — parola ~/.netrc'den geldiği için Claude Code'un da parolaya
ihtiyacı olmaz.

-----------------------------------------------------------
SORUN GİDERME
-----------------------------------------------------------
- "parola sordu" / giriş başarısız:  ~/.netrc kurulmamış olabilir. ADIM 1'i çalıştırın.
- Sayfa açılmıyor (ama yükleme başarılı):  FTP hesabı alt klasöre düşüyor olabilir.
  deploy.sh içindeki REMOTE_DIR="." satırını REMOTE_DIR="/public_html/deneme"
  (veya hostunuzun verdiği yol) olarak değiştirip tekrar çalıştırın.
- İlk kurulumdan sonra /admin adresine girip yönetici şifrenizi belirleyin.
