# Medisca Admin - Monitoring Bug, Error & Keamanan Sistem UKS Digital

Dashboard pengawasan khusus untuk platform **Medisca** (*"Teman Sehat di Sekolah"*), yang dirancang untuk memantau integritas teknis, stabilitas formulir skrining kesehatan siswa, serta keamanan privasi data rekam medis UKS.

---

## 🎨 Penyesuaian Identitas Brand Medisca

Dashboard ini telah disesuaikan secara menyeluruh dengan tampilan dan konsep website **Medisca**:
- **Logo & Tipografi**: Menggunakan logo salib medis khas Medisca dengan palet warna resmi (*Deep Pine Teal* `#082421`, *Medical Teal* `#0d9488`, dan *Vibrant Mint* `#14b8a6` / `#2dd4bf`).
- **Tagline Resmi**: *"Teman Sehat di Sekolah - Layanan Informasi & UKS Digital"*.
- **Kemitraan Terpadu**: Integrasi monitoring terhadap modul Puskesmas, PMI Remaja, Dinas Kesehatan, dan Komite Sekolah.

---

## 🛡️ Fitur Khusus Platform Medisca

### 1. Deteksi Aktivitas Mencurigakan & Ancaman Privasi Siswa
- **SQL Injection pada Data Rekam Medis**: Mendeteksi percobaan penyusupan pada endpoint skrining siswa (`/api/v1/skrining/rekam-medis?nisn=...`).
- **Brute Force Akun Petugas UKS**: Melindungi portal login Guru Pembina UKS dan Dokter Sekolah (`/auth/login-petugas-uks`).
- **Pencegahan Akses Ilegal Surat Rujukan**: Mencegah eksploitasi IDOR pada dokumen rujukan siswa ke Puskesmas.
- **Spam Bot Konsultasi Dokter Kecil**: Memfilter spam iklan yang masuk ke antrean formulir tanya dokter sekolah.
- **Tindakan Cepat**: Tombol **"Blokir IP Sekarang"** yang langsung memasukkan IP penyerang ke firewall blacklist Medisca.

### 2. Log Bug & Pengecualian Error Medisca
- **Error Skrining & Kalkulator BMI**: Menangkap bug seperti kalkulator status gizi menghasilkan `NaN` saat input koma pada tinggi badan siswa.
- **Timeout API Integrasi Puskesmas**: Memantau kegagalan kirim rujukan digital akibat server Puskesmas overload (HTTP 502).
- **Gateway Notifikasi WhatsApp Wali Murid**: Mendeteksi kegagalan pengiriman rekapitulasi kartu sehat ke nomor orang tua siswa.
- **Inspeksi Stack Trace Lengkap**: Menyajikan baris kode penyebab error, NISN siswa terdampak, dan lingkungan server.

### 3. Simulator Real-Time Khusus Medisca
Anda dapat mengklik tombol **"Simulasi Serangan / Error"** pada sidebar untuk menguji:
- Percobaan SQLi pencurian rekam medis siswa.
- Serangan Brute Force login guru pembina UKS.
- Kegagalan sinkronisasi rujukan Puskesmas.
- Bug formulir kalkulasi BMI siswa.

---

## 🚀 Cara Menjalankan

Cukup **klik dua kali file `index.html`** di folder `c:\Users\Nadira\Downloads\admin` untuk membukanya langsung di browser apa pun.
