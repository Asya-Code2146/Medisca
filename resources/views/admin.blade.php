<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Admin Medisca - Laporan Bug, Error &amp; Keamanan</title>
  <link rel="icon" type="image/png" href="/logo.png">
  <link rel="stylesheet" href="/admin/styles.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>

  <header class="navbar">
    <div class="nav-container">
      <div class="brand">
        <img class="brand-logo" src="/logo.png" alt="Logo Medisca">
        <div class="brand-text">
          <h2>Medisca</h2>
          <span>Admin Website</span>
        </div>
      </div>

      <div class="nav-right">
        <span class="status-pill"><span class="dot"></span> Website Normal</span>
        <div class="user-badge">
          <div class="avatar">A</div>
          <span>{{ auth()->user()->name ?? 'Admin UKS' }}</span>
        </div>
        <a href="/" class="btn btn-secondary btn-sm" style="margin-right: 4px;" title="Lihat Aplikasi UKS">
          Dasbor UKS
        </a>
        <a href="#" class="btn btn-logout btn-sm" id="adminLogout" title="Keluar ke Halaman Utama">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
          Keluar
        </a>
      </div>
    </div>
  </header>

  <main class="main-container">
    
    <div class="hero-section">
      <h1>Pantau Masalah Website</h1>
      <p>Lihat laporan bug tampilan, error sistem, dan aktivitas yang mencurigakan di Medisca.</p>
    </div>

    <div class="summary-grid">
      <div class="summary-card card-bug" onclick="filterCategory('bug')">
        <div class="card-icon">&#128027;</div>
        <div class="card-info">
          <span class="card-title">Bug Website</span>
          <h3 id="count-bug">0</h3>
          <p>Tombol / tampilan bermasalah</p>
        </div>
      </div>

      <div class="summary-card card-error" onclick="filterCategory('error')">
        <div class="card-icon">&#9888;&#65039;</div>
        <div class="card-info">
          <span class="card-title">Error Sistem</span>
          <h3 id="count-error">0</h3>
          <p>Server gagal proses / macet</p>
        </div>
      </div>

      <div class="summary-card card-suspicious" onclick="filterCategory('mencurigakan')">
        <div class="card-icon">&#128680;</div>
        <div class="card-info">
          <span class="card-title">Aktivitas Mencurigakan</span>
          <h3 id="count-suspicious">0</h3>
          <p>Percobaan login gagal / serangan</p>
        </div>
      </div>
    </div>

    <div class="table-card">
      <div class="table-header">
        <div class="tab-filters">
          <button class="tab-btn active" data-filter="all" onclick="filterCategory('all')">Semua Laporan (<span id="total-count">0</span>)</button>
          <button class="tab-btn" data-filter="bug" onclick="filterCategory('bug')">Bug</button>
          <button class="tab-btn" data-filter="error" onclick="filterCategory('error')">Error</button>
          <button class="tab-btn" data-filter="mencurigakan" onclick="filterCategory('mencurigakan')">Mencurigakan</button>
        </div>

        <div class="search-box">
          <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
          <input type="text" id="searchInput" placeholder="Cari masalah atau halaman..." onkeyup="filterItems()">
        </div>
      </div>

      <div class="table-responsive">
        <table class="report-table">
          <thead>
            <tr>
              <th>Waktu</th>
              <th>Kategori</th>
              <th>Masalah yang Ditemukan</th>
              <th>Halaman Website</th>
              <th>Status</th>
              <th style="text-align: right;">Aksi</th>
            </tr>
          </thead>
          <tbody id="reportTableBody">
          </tbody>
        </table>
      </div>

      <div class="table-empty" id="emptyState" style="display: none;">
        <p>Tidak ada laporan yang sesuai.</p>
      </div>
    </div>

  </main>

  <div class="modal-overlay" id="detailModal">
    <div class="modal-box">
      <div class="modal-top">
        <h3 id="modalTitle">Detail Masalah</h3>
        <button class="close-btn" onclick="closeModal()">&times;</button>
      </div>
      <div class="modal-content">
        <div class="modal-row">
          <span class="label">Kategori:</span>
          <span id="modalCategory" class="badge">Bug</span>
        </div>
        <div class="modal-row">
          <span class="label">Halaman:</span>
          <strong id="modalPage">/skrining</strong>
        </div>
        <div class="modal-row">
          <span class="label">Waktu Kejadian:</span>
          <span id="modalTime">10 menit lalu</span>
        </div>
        <div class="modal-desc-box">
          <span class="label">Penjelasan:</span>
          <p id="modalDesc">Tombol kirim tidak merespon saat siswa menggunakan ponsel Android.</p>
        </div>
        <div class="modal-solusi-box">
          <span class="label">Saran Tindakan:</span>
          <p id="modalSolution">Periksa koneksi form atau bersihkan cache browser siswa.</p>
        </div>
      </div>
      <div class="modal-bottom">
        <button class="btn btn-secondary" onclick="closeModal()">Tutup</button>
        <button class="btn btn-primary" id="btnToggleStatus" onclick="toggleCurrentStatus()">Tandai Selesai</button>
      </div>
    </div>
  </div>

  <script src="/admin/app.js"></script>
</body>
</html>
