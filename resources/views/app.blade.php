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
          <a href="#top">Instagram</a><a href="#top">Facebook</a><a href="#top">TikTok</a>
        </div>

        <div>
          <h4>Kontak</h4>
          <a href="#top">uks@sekolah.sch.id</a><a href="#top">Ruang UKS, lantai 1</a>
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
