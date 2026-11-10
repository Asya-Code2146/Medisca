<!doctype html>
<html lang="id">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0f4c45">
    <title>Medisca – Sistem Informasi UKS Sekolah</title>
    <link rel="icon" type="image/png" href="/logo.png" />
    <link rel="manifest" href="/manifest.json" />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@500;700;800&family=Figtree:wght@400;500;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="/style.css" />
  </head>

  <body>

    <div id="splash-screen">
      <div class="splash-logo-wrapper">
        <div class="splash-pulse-ring"></div>
        <img src="/logo.png" alt="Logo Medisca" class="splash-logo-img" />
      </div>
      <div class="splash-text-wrapper">
        <h1 class="splash-title">Medisca</h1>
        <p class="splash-subtitle">Sistem Informasi UKS</p>
      </div>
      <div class="splash-loader"><div class="splash-loader-bar"></div></div>
    </div>

    <nav>
      <div class="w">
        <a class="logo" href="#top"><img class="logo-img" src="/logo.png" alt="Logo Medisca" />Medisca</a>

        <div class="l">
          <a href="#fitur">Fitur</a>
          <a href="#mengapa">Tujuan &amp; Manfaat</a>
          <a href="#contact">Contact</a>
          <a href="#faq">FAQ</a>
        </div>

        <div style="display: flex; align-items: center; gap: 10px;">
          <button class="btn-install" id="btnInstallApp" style="display:none;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
            Install Aplikasi
          </button>
          <button class="btn" id="navLogin">Masuk</button>
        </div>
      </div>
    </nav>

    <main id="top">

      <div id="app">
        <div class="w">

          <div
            style="
              display: flex;
              justify-content: space-between;
              align-items: center;
              gap: 12px;
              flex-wrap: wrap;
              margin-bottom: 8px;
            "
          >
            <div>
              <h2 style="text-align: left; font-size: 30px">Dasbor UKS</h2>
              <span style="color: var(--mut)" id="who"></span>
            </div>
            <button class="btn o" id="out">Keluar</button>
          </div>

          <div class="tabs" role="tablist" id="tabs"></div>

          <div id="view"></div>

        </div>
      </div>

      <div id="site">

        <section class="hero" id="home">
          <div class="container">
            <div class="hero-card">
              <div class="hero-photo" role="img" aria-label="Foto suasana layanan kesehatan"></div>
              
              <div class="hero-content">
                <p class="eyebrow">Teman sehat di sekolah</p>
                <h1>Mulai langkah kecil untuk hidup <span>lebih sehat.</span></h1>
                <p class="hero-description">
                  Medisca adalah halaman informasi kesehatan sekolah yang membantu siswa mengenal kebiasaan hidup sehat,
                  menjaga kebersihan diri, dan memahami peran UKS dalam lingkungan sekolah.
                </p>
                <div class="hero-buttons">
                  <a class="btn btn-primary" href="#about">Jelajahi Medisca <span aria-hidden="true">&rarr;</span></a>
                  <a class="btn btn-light-outline" href="login.html" data-login>Masuk ke akun</a>
                </div>
                <p class="hero-note">Informasi kesehatan yang sederhana, dekat, dan mudah dipahami.</p>
              </div>
            </div>
          </div>
        </section>

        <div class="part" id="about">
          <div class="w">
            <p>Bekerja bersama pihak yang peduli kesehatan sekolah</p>
            <div>
              <span>Puskesmas</span><span>PMI Remaja</span><span>Dinas Kesehatan</span><span>Komite Sekolah</span><span>Dokter Kecil</span>
            </div>
          </div>
        </div>

        <section id="fitur">
          <div class="w">
            <h2>Semua kebutuhan UKS, satu tempat</h2>
            <p class="sub">
              Dari stok kapas sampai laporan bulanan, tersusun dan mudah dicari.
            </p>

            <div class="feat">
              <div class="fc">
                <span class="ic">&#128138;</span>
                <h3>Stok obat &amp; perlengkapan</h3>
                <p>
                  23 item tercatat. Tambah atau kurangi stok, lihat peringatan
                  saat menipis.
                </p>
              </div>
              <div class="fc">
                <span class="ic">&#129489;&#8205;&#127891;</span>
                <h3>Data siswa sakit</h3>
                <p>Catat keluhan, obat yang diberikan, dan status pulih atau dirujuk.</p>
              </div>
            </div>

            <div class="feat feat2" style="margin-top: 16px">
              <div class="fc">
                <span class="ic">&#129658;</span>
                <h3>Panduan gejala</h3>
                <p>Penanganan awal 10 keluhan umum.</p>
              </div>
              <div class="fc">
                <span class="ic">&#128202;</span>
                <h3>Laporan &amp; edukasi</h3>
                <p>
                  Rekap keluhan terbanyak dan materi hidup sehat, termasuk
                  pencegahan HIV.
                </p>
              </div>
            </div>
          </div>
        </section>

        <section id="mengapa">
          <div class="w">
            <h2>Mengapa petugas memilih Medisca</h2>
            <p class="sub">
              Dibuat untuk ritme UKS yang sibuk saat jam istirahat.
            </p>
            <div class="g4">
              <div class="wy">
                <i>&#9889;</i>
                <h3>Catat dalam hitungan detik</h3>
                <p>Formulir singkat, cukup nama, kelas, keluhan.</p>
              </div>
              <div class="wy">
                <i>&#128276;</i>
                <h3>Stok tidak kehabisan</h3>
                <p>Item di bawah batas minimum ditandai merah.</p>
              </div>
              <div class="wy">
                <i>&#128195;</i>
                <h3>Laporan siap pakai</h3>
                <p>Rekap otomatis dari catatan kunjungan.</p>
              </div>
              <div class="wy">
                <i>&#128218;</i>
                <h3>Edukasi bagi siswa</h3>
                <p>Materi kesehatan remaja yang jelas dan tanpa stigma.</p>
              </div>
            </div>
          </div>
        </section>

        <section class="faq" id="faq">
          <div class="w">
            <div>
              <h2>Pertanyaan umum</h2>
              <p style="color: var(--mut)">
                Belum menemukan jawabannya? Hubungi kami lewat kontak di bawah.
              </p>
            </div>
            <div>
              <details>
                <summary>Siapa yang bisa masuk?</summary>
                <p>
                  Petugas UKS, guru pembina, dan anggota PMR yang diberi akun
                  oleh sekolah.
                </p>
              </details>
              <details>
                <summary>Apakah Medisca memberi diagnosis?</summary>
                <p>
                  Tidak. Panduan gejala hanya penanganan awal. Kondisi berat
                  harus dirujuk ke tenaga kesehatan.
                </p>
              </details>
              <details>
                <summary>Bagaimana data siswa dijaga?</summary>
                <p>
                  Data kesehatan bersifat rahasia dan hanya dibuka bagi petugas
                  berwenang.
                </p>
              </details>
              <details>
                <summary>Bisakah stok obat diperbarui sendiri?</summary>
                <p>
                  Bisa. Gunakan tombol tambah dan kurang, atau pilih obat saat
                  mencatat siswa sakit agar stok berkurang otomatis.
                </p>
              </details>
            </div>
          </div>
        </section>

        <div class="w">
          <div class="cta">
            <div>
              <h2>Mulai rapikan UKS hari ini</h2>
              <p>Masuk dan coba semua fitur dengan akun demo.</p>
            </div>
            <button class="btn" data-login>Mulai sekarang</button>
          </div>
        </div>

      </div>

    </main>

    <footer>
      <div class="w" id="contact">
        <div>
          <a class="logo" href="#top"><img class="logo-img" src="/logo.png" alt="Logo Medisca" />Medisca</a>
          <p>
            Sistem informasi Unit Kesehatan Sekolah untuk sekolah yang peduli.
          </p>
        </div>

        <div>
          <h4>Tautan utama</h4>
          <a href="#fitur">Fitur</a><a href="#mengapa">Mengapa Medisca</a><a href="#faq">FAQ</a>
        </div>

        <div>
          <h4>Media sosial</h4>
          <div class="social-icons">
            <a href="https://www.instagram.com/smkn5telkomaceh/" target="_blank" rel="noopener noreferrer" title="Instagram @smkn5telkomaceh" class="social-btn" aria-label="Instagram">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
            </a>
            <a href="https://www.facebook.com/search/top?q=Smk%20Negeri%205%20Telkom%20Banda%20Aceh" target="_blank" rel="noopener noreferrer" title="Facebook SMK Negeri 5 Telkom Banda Aceh" class="social-btn" aria-label="Facebook">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>
            </a>
            <a href="https://www.tiktok.com/@smkn5telkomaceh/video/7395022131928583430" target="_blank" rel="noopener noreferrer" title="TikTok @smkn5telkomaceh" class="social-btn" aria-label="TikTok">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1.04-.1z"/></svg>
            </a>
          </div>
        </div>

        <div>
          <h4>Kontak</h4>
          <a href="mailto:smkn5telkombandaaceh@gmail.com" class="contact-item">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
            smkn5telkombandaaceh@gmail.com
          </a>
          <a href="tel:06517552314" class="contact-item">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
            0651-7552314
          </a>
          <span class="contact-item">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
            Ruang UKS, Lantai 1
          </span>
        </div>

        <div class="cp">
          &copy; 2026 Medisca. Informasi di situs ini bukan pengganti saran dokter.
        </div>
      </div>
    </footer>

    <div
      class="modal"
      id="lm"
      role="dialog"
      aria-modal="true"
      aria-labelledby="lt"
    >
      <div class="box">
        <h3 id="lt">Masuk ke Medisca</h3>
        <span style="color: var(--mut); font-size: 14px">Masuk sebagai Petugas UKS, Pihak Sekolah, atau Admin.</span>

        <div class="hint">
          Petugas UKS: <b>petugas</b> / <b>uks123</b><br>
          Pihak Sekolah: <b>sekolah</b> / <b>sekolah123</b><br>
          Admin: <b>admin</b> / <b>admin123</b>
        </div>

        <label for="u">Nama pengguna atau Email</label><input id="u" autocomplete="username" />

        <label for="p">Kata sandi</label><input id="p" type="password" autocomplete="current-password" />

        <div class="err" id="er"></div>

        <div style="display: flex; gap: 8px">
          <button class="btn" id="go" style="flex: 1">Masuk</button><button class="btn o" id="cx">Batal</button>
        </div>

        <div class="auth-divider"><span>atau</span></div>

        <a href="/auth/google" class="btn-google">
          <svg width="18" height="18" viewBox="0 0 24 24"><path fill="#4285F4" d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.82-2.4 3.68v3.05h3.88c2.27-2.09 3.665-5.17 3.665-9.17z"/><path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.1-6.72-4.93H1.25v3.15C3.26 21.36 7.33 24 12 24z"/><path fill="#FBBC05" d="M5.28 14.27c-.25-.72-.38-1.49-.38-2.27s.13-1.55.38-2.27V6.58H1.25C.45 8.18 0 10.03 0 12s.45 3.82 1.25 5.42l4.03-3.15z"/><path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.33 0 3.26 2.64 1.25 6.58l4.03 3.15c.95-2.83 3.6-4.98 6.72-4.98z"/></svg>
          Masuk dengan Google
        </a>
      </div>
    </div>

    <script src="/script.js"></script>

  </body>
</html>
