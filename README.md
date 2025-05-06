# 📺t Plugin WordPress Telly

**Telly** adalah plugin WordPress yang memudahkan Anda menampilkan video atau playlist YouTube langsung di halaman atau postingan menggunakan shortcode. Dilengkapi dengan sistem caching, kontrol tampilan, dan desain responsif, plugin ini cocok untuk blogger, content creator, hingga website institusi.


## 🎯 **Fitur Utama**

* Menampilkan **single video** YouTube dengan kontrol ukuran.
* Menampilkan **playlist YouTube** dengan pilihan tampilan 1, 2, atau 3 kolom.
* Batas jumlah video yang ditampilkan (default 10).
* Tombol **"Load More"** untuk playlist panjang.
* **Sistem caching otomatis** untuk mengurangi beban API.
* **Halaman pengaturan** untuk memasukkan API Key YouTube.
* **Desain responsif** yang kompatibel di semua perangkat.


## 🛠 **Instalasi**

1. **Download** plugin ini dalam format `.zip`.
2. **Upload melalui WordPress Admin Panel**:

   * Masuk ke menu **Plugins > Add New > Upload Plugin**.
   * Pilih file zip dan klik **Install Now**.
   * Aktifkan plugin.
3. **Atau upload manual**:

   * Ekstrak folder plugin dan unggah ke direktori `wp-content/plugins/` via FTP.
   * Aktifkan plugin melalui menu **Plugins** di WordPress.


## ⚙️ **Konfigurasi**

1. Masuk ke menu **Settings > Telly** di dashboard WordPress.
2. Masukkan **YouTube API Key** Anda.
3. Atur **durasi cache** sesuai kebutuhan untuk mengontrol frekuensi fetch data dari YouTube API.


## 🔗 **Shortcode & Parameter**

### ▶️ Menampilkan Single Video

```php
[telly_video id="VIDEO_ID" width="100%" height="400"]
```

**Contoh:**

```php
[telly_video id="dQw4w9WgXcQ" width="100%" height="400"]
```

### 📺 Menampilkan Playlist

```php
[telly_playlist id="PLAYLIST_ID" columns="3" limit="10" show_more="true"]
```

**Parameter Penjelasan:**

* `columns`: Jumlah kolom tampilan (1, 2, atau 3)
* `limit`: Jumlah video yang ditampilkan saat awal (default: 10)
* `show_more`: `true` / `false` untuk menampilkan tombol **"Load More"**

**Contoh:**

```php
[telly_playlist id="PL9tY0BWXOZFtdmRJZfQYkRZ3TTdp0rrZ8" columns="2" limit="6" show_more="true"]
```


## 📷 **Screenshot**

*Tambahkan screenshot folder `/ss/` jika tersedia di repositori GitHub:*

* Shortcode
![Shortcode](https://raw.githubusercontent.com/kelaskakap/Telly/master/ss/ss1.JPG)
* Tampilan playlist 2 kolom
![2 kolom](https://raw.githubusercontent.com/kelaskakap/Telly/master/ss/ss2.JPG)
* Halaman pengaturan API Key
![Pengaturan](https://raw.githubusercontent.com/kelaskakap/Telly/master/ss/ss3.JPG)


## 💡 **Tips Penggunaan**

* Gunakan playlist untuk membuat **galeri video edukasi, promosi, atau vlog**.
* Gunakan caching untuk **menghemat kuota API YouTube**.
* Optimalkan dengan plugin cache eksternal untuk performa maksimal.


## 🚀 **Pengembangan Selanjutnya**

* Integrasi tema gelap/terang otomatis.
* Fitur pencarian dalam playlist.
* Dukungan YouTube Shorts.
* Integrasi data analytics ringan.


## 📜 **Lisensi**

Plugin ini dirilis dengan lisensi **MIT**. Bebas digunakan, dimodifikasi, dan dibagikan.


## 🤝 **Kontribusi**

Jika Anda ingin berkontribusi, silakan fork repositori ini dan buat pull request! Saran dan masukan sangat diterima.


## 🍹 **Trakteer Saya**
Jika kamu suka dengan repositori ini dan ingin mendukung saya, bisa traktir saya cendol di sini:

[![Trakteer](https://img.shields.io/badge/🍹%20Trakteer%20Saya-red?style=for-the-badge)](https://teer.id/kiosmerdeka)

---

✨ **Dibuat dengan semangat open-source dan cinta untuk video edukatif!** ✨
