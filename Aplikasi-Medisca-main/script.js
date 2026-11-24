const $ = (s) => document.querySelector(s);
const $$ = (s) => document.querySelectorAll(s);
const esc = (s) => String(s || '').replace(/[&<>"]/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' })[c]);

let currentUser = null;
let meds = [];
let sick = [];
let categories = [];
let deferredPrompt = null;
let cur = 'stok';
let q = '';

const getCsrfToken = () => {
  const meta = $('meta[name="csrf-token"]');
  return meta ? meta.getAttribute('content') : '';
};

const showToast = (msg, type = 'success') => {
  let c = document.getElementById('toast-container');
  if (!c) {
    c = document.createElement('div');
    c.id = 'toast-container';
    c.className = 'toast-container';
    document.body.appendChild(c);
  }
  const t = document.createElement('div');
  t.className = `toast-item toast-${type}`;
  const icon = type === 'success' ? '✓' : type === 'warning' ? '⚠' : '✕';
  t.innerHTML = `<span class="toast-icon">${icon}</span><span class="toast-text">${esc(msg)}</span>`;
  c.appendChild(t);
  setTimeout(() => t.classList.add('toast-show'), 10);
  setTimeout(() => {
    t.classList.remove('toast-show');
    setTimeout(() => {
      if (t.parentNode) t.parentNode.removeChild(t);
    }, 300);
  }, 3200);
};
window.showToast = showToast;

const sy = [
  ['Demam', '🌡️', 'Ukur suhu, istirahat, minum cukup, kompres hangat. Obat: Paracetamol sesuai aturan pakai.', 'Suhu tinggi menetap, kejang, atau lemas berat.'],
  ['Flu', '🤧', 'Istirahat, minum air hangat, hirup Minyak Kayu Putih. Pakai masker agar tidak menular.', 'Sesak napas atau demam lebih dari 3 hari.'],
  ['Batuk', '😷', 'Minum air hangat, hindari debu. Obat: Ambroxol bila berdahak.', 'Batuk berdarah atau lebih dari 2 minggu.'],
  ['Sakit gigi', '🦷', 'Berkumur air hangat, jangan menempelkan obat pada gusi. Obat: Ibuprofen atau Asam Mefenamat bila perlu.', 'Bengkak pada wajah atau nyeri tak reda.'],
  ['Kecelakaan kecil / jatuh', '🩹', 'Hentikan perdarahan dengan kasa, bersihkan luka dengan air bersih, beri Betadine, tutup Hansaplast. Memar: kompres dingin.', 'Luka dalam, patah tulang, pingsan, atau benturan kepala.'],
  ['Alergi', '🌼', 'Jauhkan dari pemicu, tenangkan siswa. Obat: Cetirizine. Gatal ringan boleh diberi Fresh Care atau krim kulit.', 'Bibir atau wajah bengkak, sulit bernapas.'],
  ['Lambung, maag', '🍽️', 'Istirahat, makan teratur sedikit-sedikit. Obat: Antasida, hindari pedas dan asam.', 'Muntah terus, nyeri hebat, atau tinja hitam.'],
  ['Diare', '💧', 'Minum cukup cairan, jaga kebersihan tangan. Obat: Diatab atau Entrostop sesuai aturan pakai.', 'Lemas, tanda dehidrasi, atau ada darah pada tinja.'],
  ['Kekurangan darah', '🩸', 'Istirahat berbaring, beri minum. Anjurkan Tablet Tambah Darah rutin, makanan kaya zat besi.', 'Pucat parah, pingsan, atau sering pusing.'],
  ['Nyeri haid', '🌙', 'Istirahat, kompres hangat perut. Obat: Feminax atau Asam Mefenamat sesuai aturan.', 'Nyeri hebat atau haid sangat banyak.']
];

const rules = [
  'UKS dibuka pada jam sekolah dan jam istirahat. Petugas wajib hadir bergantian.',
  'Siswa sakit meminta izin guru kelas dan membawa kartu izin ke UKS.',
  'Setiap kunjungan dicatat: nama, kelas, keluhan, tindakan, dan obat.',
  'Obat hanya diberikan petugas atau pembina, bukan diambil sendiri.',
  'Cek tanggal kedaluwarsa obat setiap awal bulan dan buang yang kedaluwarsa.',
  'Siswa yang tidak membaik dalam 1 jam atau berkondisi berat dihubungkan ke orang tua dan dirujuk.',
  'Jaga kebersihan: cuci tangan sebelum dan sesudah menangani siswa, ganti sprei rutin.',
  'Data kesehatan siswa bersifat rahasia dan tidak disebarkan.',
  'Jaga ketenangan dan kerapian ruang UKS, tempat tidur dirapikan setelah dipakai.'
];

const edu = [
  ['Tablet Tambah Darah', 'Remaja putri dianjurkan minum TTD secara rutin sesuai program sekolah. Imbangi dengan sayur hijau, daging, telur, dan buah. Hindari minum bersama teh atau kopi.'],
  ['Cuci tangan pakai sabun', 'Cuci tangan sebelum makan dan sesudah dari toilet selama 20 detik. Ini mencegah diare, flu, dan penyakit menular lainnya.'],
  ['Makan dan tidur cukup', 'Sarapan sebelum sekolah, kurangi jajanan pedas dan asam, tidur 8 jam, dan aktif bergerak agar tidak mudah sakit.'],
  ['Kenali HIV dan AIDS', 'HIV melemahkan daya tahan tubuh. Menular lewat hubungan seksual tanpa pengaman, darah (jarum suntik, tato, atau alat tajam bergantian), dan dari ibu ke bayi. HIV tidak menular lewat salaman, pelukan, makan bersama, gigitan nyamuk, atau duduk bersebelahan.'],
  ['Cara mencegah HIV', 'Hindari seks bebas, jangan berbagi jarum, pisau cukur, atau alat tindik. Gunakan hanya peralatan steril, cari informasi dari sumber tepercaya, dan menolak ajakan berisiko itu hal yang berani.'],
  ['Tes dan tanpa stigma', 'Tes HIV dapat dilakukan sukarela dan rahasia di Puskesmas atau klinik. Penderita bisa hidup sehat dengan pengobatan ARV. Jangan menjauhi atau mengejek teman, dukung mereka.']
];

const tabs = [
  ['stok', '💊 Stok obat'],
  ['sakit', '🧑‍🎓 Siswa sakit'],
  ['gej', '🩺 Gejala'],
  ['atur', '📜 Aturan UKS'],
  ['lap', '📊 Laporan'],
  ['edu', '📚 Edukasi']
];

const gOpt = () => {
  if (categories && categories.length > 0) {
    return categories.map((c) => `<option value="${c.id}">${esc(c.nama_keluhan)}</option>`).join('');
  }
  return sy.map((s) => `<option value="">${esc(s[0])}</option>`).join('');
};

const oOpt = () =>
  '<option value="">Tanpa obat</option>' +
  meds.map((m) => `<option value="${m.id}">${esc(m.nama_obat)} (Sisa: ${m.stok})</option>`).join('');

async function loadData() {
  try {
    const [resMeds, resSick, resCats] = await Promise.all([
      fetch('/medicines', { headers: { Accept: 'application/json' } }),
      fetch('/medical-records', { headers: { Accept: 'application/json' } }),
      fetch('/complaint-categories', { headers: { Accept: 'application/json' } })
    ]);

    if (resMeds.ok) {
      const json = await resMeds.json();
      meds = json.data || [];
    }
    if (resSick.ok) {
      const json = await resSick.json();
      sick = json.data || [];
    }
    if (resCats.ok) {
      const json = await resCats.json();
      categories = json.data || [];
    }
  } catch (err) {
  }
  render();
}

function render() {
  const tabsEl = $('#tabs');
  if (tabsEl) {
    tabsEl.innerHTML = tabs
      .map((t) => `<button role="tab" aria-selected="${t[0] === cur}" data-t="${t[0]}">${t[1]}</button>`)
      .join('');
  }

  const v = $('#view');
  if (!v) return;

  const isReadOnly = currentUser && currentUser.role === 'pihak_sekolah';
  let h = '';

  if (isReadOnly && (cur === 'stok' || cur === 'sakit' || cur === 'lap')) {
    h += `<div class="alert-banner">
      <span>🛡️</span>
      <div>
        <strong>Mode Monitoring (Pihak Sekolah)</strong>
        <p style="margin:2px 0 0 0;font-size:13px;">Anda memiliki hak akses pemantauan dan unduh laporan. Tindakan perubahan data dikunci demi keamanan.</p>
      </div>
    </div>`;
  }

  if (cur === 'stok') {
    const criticalList = meds.filter((m) => m.status_stok === 'kritis');
    const lowList = meds.filter((m) => m.status_stok === 'menipis');

    h += `<div class="panel"><h3>Stok obat dan perlengkapan</h3><div class="row">
      <div class="stat"><b>${meds.length}</b>Jenis item</div>
      <div class="stat"><b style="color:var(--co);">${criticalList.length}</b>Stok kritis</div>
      <div class="stat"><b>${lowList.length}</b>Stok menipis</div>
      <div><label for="sq">Cari item</label><input id="sq" value="${esc(q)}" placeholder="Contoh: betadine"></div>
    </div>
    <div class="tw"><table><tr><th>Nama</th><th>Satuan</th><th>Stok</th><th>Status</th>${isReadOnly ? '' : '<th>Ubah</th>'}</tr>
    ${meds
      .filter((m) => m.nama_obat.toLowerCase().includes(q.toLowerCase()))
      .map(
        (m) => `<tr>
          <td><b>${esc(m.nama_obat)}</b></td>
          <td>${esc(m.satuan)}</td>
          <td>${m.stok}</td>
          <td><span class="pill ${m.status_stok === 'kritis' || m.status_stok === 'menipis' ? 'lo' : ''}">${esc(m.status_stok.toUpperCase())}</span></td>
          ${
            isReadOnly
              ? ''
              : `<td><button class="sm" data-action="decrease" data-id="${m.id}" aria-label="Kurangi ${esc(m.nama_obat)}">–</button> <button class="sm" data-action="increase" data-id="${m.id}" aria-label="Tambah ${esc(m.nama_obat)}">+</button></td>`
          }
        </tr>`
      )
      .join('')}</table></div></div>`;
  }

  if (cur === 'sakit') {
    const today = new Date().toISOString().split('T')[0];
    h += `<div class="panel"><h3>Data siswa sakit</h3>`;

    if (!isReadOnly) {
      h += `<form id="fs" class="row">
        <div><label>Tanggal</label><input type="date" name="tanggal" required value="${today}"></div>
        <div><label>Nama Siswa</label><input name="nama_pasien" required placeholder="Nama siswa"></div>
        <div><label>Kelas</label><input name="kelas_pasien" required placeholder="Contoh: X IPA 1"></div>
        <div><label>Kategori Keluhan</label><select name="complaint_category_id">${gOpt()}</select></div>
        <div><label>Detail Keluhan</label><input name="keluhan" required placeholder="Keluhan yang dirasakan"></div>
        <div><label>Obat diberikan</label><select name="medicine_id">${oOpt()}</select></div>
        <div><label>Jumlah Obat</label><input type="number" name="jumlah_obat" min="1" value="1"></div>
        <div><label>Tindakan</label><input name="tindakan" placeholder="Penanganan yang diberikan"></div>
        <div><label>Status</label><select name="status"><option value="sedang_dirawat">Sedang Dirawat / Istirahat</option><option value="selesai">Selesai (Kembali ke kelas)</option><option value="dirujuk">Dirujuk ke Puskesmas</option><option value="pending">Pending</option></select></div>
        <button class="btn" style="align-self:flex-end;">Simpan Rekam Medis</button>
      </form>`;
    }

    h += `<div class="tw"><table>
      <tr>
        <th>Waktu</th>
        <th>Nama</th>
        <th>Kelas</th>
        <th>Keluhan</th>
        <th>Obat Diberikan</th>
        <th>Tindakan</th>
        <th>Status</th>
        <th style="text-align:center;">Dispensasi</th>
      </tr>
      ${sick
        .map(
          (s) => `<tr>
        <td>${s.created_at ? esc(s.created_at.substring(0, 10)) : '-'}</td>
        <td><b>${esc(s.nama_pasien)}</b></td>
        <td>${esc(s.kelas_pasien || '-')}</td>
        <td>${esc(s.keluhan)}</td>
        <td>${esc(s.medicine ? s.medicine.nama_obat : 'Tanpa obat')}</td>
        <td>${esc(s.tindakan || '-')}</td>
        <td><span class="pill ${s.status === 'dirujuk' ? 'lo' : ''}">${esc(s.status.replace('_', ' '))}</span></td>
        <td style="text-align:center; white-space:nowrap;">
          <button type="button" class="btn-dispensasi ${s.status === 'dirujuk' || s.status === 'sedang_dirawat' ? 'btn-dispensasi-urgent' : ''}" onclick="printDispensasiLetter(${s.id})" title="${s.status === 'dirujuk' ? 'Kondisi Parah: Cetak Surat Rujukan Medis' : s.status === 'sedang_dirawat' ? 'Perawatan Khusus: Cetak Surat Sakit' : 'Cetak Surat Dispensasi UKS'}">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9V2h12v7"></path><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><path d="M6 14h12v8H6z"></path></svg>
            ${s.status === 'dirujuk' ? 'Surat Rujukan' : s.status === 'sedang_dirawat' ? 'Surat Sakit' : 'Surat Izin'}
          </button>
        </td>
      </tr>`
        )
        .join('')}
    </table></div></div>`;
  }

  if (cur === 'gej') {
    h += `<div class="panel"><h3>Panduan gejala penyakit</h3><div class="cards">${sy
      .map((s) => `<article><h4>${s[1]} ${s[0]}</h4><p><b>Penanganan:</b> ${s[2]}</p><p><b>Rujuk bila:</b> ${s[3]}</p></article>`)
      .join('')}</div><p style="font-size:13px;color:var(--mut)">Panduan ini penanganan awal, bukan diagnosis. Dosis mengikuti aturan pakai pada kemasan atau arahan tenaga kesehatan.</p></div>`;
  }

  if (cur === 'atur') {
    h += `<div class="panel"><h3>Aturan UKS</h3><ol class="ru">${rules.map((r) => `<li>${r}</li>`).join('')}</ol></div>`;
  }

  if (cur === 'lap') {
    const c = {};
    sick.forEach((x) => (c[x.keluhan] = (c[x.keluhan] || 0) + 1));
    const e = Object.entries(c).sort((a, b) => b[1] - a[1]);
    const mx = e[0] ? e[0][1] : 1;
    const low = meds.filter((m) => m.status_stok === 'menipis' || m.status_stok === 'kritis');

    h += `<div class="panel"><h3>Laporan dan Rekapitulasi</h3><div class="stat-grid">
      <div class="stat-card stat-blue">
        <div class="stat-icon-wrapper stat-icon-blue">🤒</div>
        <div class="stat-card-info">
          <b>${sick.length}</b>
          <span>Siswa Sakit Tercatat</span>
        </div>
      </div>
      <div class="stat-card stat-amber">
        <div class="stat-icon-wrapper stat-icon-amber">🛏️</div>
        <div class="stat-card-info">
          <b>${sick.filter((s) => s.status === 'sedang_dirawat').length}</b>
          <span>Sedang Dirawat di UKS</span>
        </div>
      </div>
      <div class="stat-card stat-red">
        <div class="stat-icon-wrapper stat-icon-red">🚑</div>
        <div class="stat-card-info">
          <b style="color:#dc2626;">${sick.filter((s) => s.status === 'dirujuk').length}</b>
          <span>Dirujuk ke Faskes</span>
        </div>
      </div>
      <div class="stat-card stat-orange">
        <div class="stat-icon-wrapper stat-icon-orange">💊</div>
        <div class="stat-card-info">
          <b>${low.length}</b>
          <span>Stok Obat Kritis</span>
        </div>
      </div>
      <div class="stat-card stat-teal">
        <div class="stat-icon-wrapper stat-icon-teal">📄</div>
        <div class="stat-card-info">
          <b>${sick.filter((s) => s.status === 'dirujuk' || s.status === 'sedang_dirawat').length}</b>
          <span>Surat Dispensasi Khusus</span>
        </div>
      </div>
    </div>
    <h3 style="font-size:17px;margin-top:20px;">Keluhan terbanyak</h3>${e
      .map((x) => `<div class="bar"><span>${esc(x[0])}</span><i style="width:${(x[1] / mx) * 100}%"></i><b>${x[1]}</b></div>`)
      .join('')}
    <h3 style="font-size:17px;margin-top:20px;">Peringatan Stok Obat</h3>
    <p>${low.map((m) => `<span class="pill lo">${esc(m.nama_obat)} (${m.stok} ${esc(m.satuan)})</span> `).join('') || 'Semua stok aman.'}</p>
    <div style="display:flex;gap:10px;margin-top:16px;">
      <a href="/dashboard/export" class="btn"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg> Unduh Laporan CSV</a>
      <button class="btn o" onclick="window.print()">Cetak Halaman</button>
    </div>
    </div>`;
  }

  if (cur === 'edu') {
    h += `<div class="panel"><h3>Edukasi kesehatan</h3><div class="cards">${edu
      .map((x) => `<article><h4>${x[0]}</h4><p>${x[1]}</p></article>`)
      .join('')}</div></div>`;
  }

  v.innerHTML = h;
}

async function checkAuthStatus() {
  try {
    const res = await fetch('/auth/user', { headers: { Accept: 'application/json' } });
    if (res.ok) {
      const data = await res.json();
      if (data.authenticated && data.user) {
        currentUser = data.user;
        $('#site').style.display = 'none';
        $('#app').classList.add('on');
        const roleLabel =
          currentUser.role === 'admin'
            ? 'Administrator'
            : currentUser.role === 'guru_uks'
            ? 'Petugas / Guru UKS'
            : 'Pihak Sekolah (Monitoring)';
        const roleClass =
          currentUser.role === 'admin' ? 'role-admin' : currentUser.role === 'guru_uks' ? 'role-guru' : 'role-sekolah';
        $('#who').innerHTML = `Masuk sebagai: <b>${esc(currentUser.name)}</b> <span class="role-badge ${roleClass}">${roleLabel}</span>`;
        if (currentUser.role === 'admin') {
          const adminBtn = document.createElement('a');
          adminBtn.href = '/admin';
          adminBtn.className = 'btn btn-secondary';
          adminBtn.style.marginRight = '8px';
          adminBtn.innerText = 'Panel Admin';
          const whoContainer = $('#who').parentElement;
          if (whoContainer && !$('#btnToAdmin')) {
            adminBtn.id = 'btnToAdmin';
            whoContainer.appendChild(adminBtn);
          }
        }
        $('#navLogin').style.display = 'none';
        await loadData();
        return;
      }
    }
  } catch (e) {
  }
  $('#site').style.display = '';
  $('#app').classList.remove('on');
  $('#navLogin').style.display = '';
}

function setupPwa() {
  const isStandalone =
    window.matchMedia('(display-mode: standalone)').matches ||
    window.navigator.standalone === true ||
    document.referrer.includes('android-app://');

  const btnInstall = $('#btnInstallApp');

  if (isStandalone) {
    if (btnInstall) {
      btnInstall.style.display = 'none';
    }
    return;
  }

  if (localStorage.getItem('medisca_app_installed') === 'true') {
    if (btnInstall) {
      btnInstall.innerText = 'Aplikasi Sudah Terinstal';
      btnInstall.classList.add('installed');
      btnInstall.disabled = true;
    }
  }

  window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault();
    deferredPrompt = e;
    if (btnInstall && localStorage.getItem('medisca_app_installed') !== 'true') {
      btnInstall.style.display = 'inline-flex';
      btnInstall.innerText = 'Install Aplikasi';
      btnInstall.classList.remove('installed');
      btnInstall.disabled = false;
    }
  });

  if (btnInstall) {
    btnInstall.addEventListener('click', async () => {
      if (btnInstall.classList.contains('installed')) return;

      if (deferredPrompt) {
        deferredPrompt.prompt();
        const choiceResult = await deferredPrompt.userChoice;
        if (choiceResult.outcome === 'accepted') {
          btnInstall.innerText = 'Aplikasi Sudah Terinstal';
          btnInstall.classList.add('installed');
          btnInstall.disabled = true;
          localStorage.setItem('medisca_app_installed', 'true');
        }
        deferredPrompt = null;
      } else {
        showToast('Gunakan menu browser untuk menginstal Medisca ("Install App" / "Tambahkan ke Layar Utama").', 'warning');
      }
    });
  }

  window.addEventListener('appinstalled', () => {
    if (btnInstall) {
      btnInstall.innerText = 'Aplikasi Sudah Terinstal';
      btnInstall.classList.add('installed');
      btnInstall.disabled = true;
    }
    localStorage.setItem('medisca_app_installed', 'true');
    deferredPrompt = null;
  });

  if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/sw.js').catch(() => {});
  }
}

function setupSplash() {
  const splash = $('#splash-screen');
  if (splash) {
    setTimeout(() => {
      splash.classList.add('hide');
    }, 1400);
  }
}

document.addEventListener('click', async (e) => {
  const t = e.target;

  if (t.dataset && t.dataset.t) {
    cur = t.dataset.t;
    render();
  }

  const action = t.dataset ? t.dataset.action : null;
  const medId = t.dataset ? t.dataset.id : null;
  if (action && medId) {
    if (currentUser && currentUser.role === 'pihak_sekolah') {
      showToast('Akses Ditolak: Role Pihak Sekolah hanya memiliki akses pemantauan data (Read-Only).', 'warning');
      return;
    }

    try {
      const res = await fetch(`/medicines/${medId}/stock`, {
        method: 'PATCH',
        headers: {
          'Content-Type': 'application/json',
          Accept: 'application/json',
          'X-CSRF-TOKEN': getCsrfToken()
        },
        body: JSON.stringify({ action: action, amount: 1 })
      });
      const data = await res.json();
      if (res.ok) {
        const item = meds.find((m) => m.id == medId);
        if (item) {
          item.stok = data.data.stok;
          item.status_stok = data.data.status_stok;
          showToast(`Stok ${item.nama_obat} berhasil diperbarui (${item.stok} ${item.satuan}).`, 'success');
        }
        render();
      } else {
        showToast(data.message || 'Gagal mengubah stok obat.', 'error');
      }
    } catch (err) {
      showToast('Terjadi kesalahan koneksi.', 'error');
    }
  }

  const loginTarget = t.closest ? t.closest('[data-login],#navLogin') : null;
  if (loginTarget) {
    e.preventDefault();
    $('#lm').classList.add('on');
    $('#u').focus();
  }
});

document.addEventListener('input', (e) => {
  if (e.target.id === 'sq') {
    q = e.target.value;
    const p = e.target.selectionStart;
    render();
    const s = $('#sq');
    if (s) {
      s.focus();
      s.setSelectionRange(p, p);
    }
  }
});

document.addEventListener('submit', async (e) => {
  if (e.target.id === 'fs') {
    e.preventDefault();
    if (currentUser && currentUser.role === 'pihak_sekolah') {
      showToast('Akses Ditolak: Role Pihak Sekolah hanya memiliki akses pemantauan (Read-Only).', 'warning');
      return;
    }

    const form = e.target;
    const formData = new FormData(form);
    const payload = {
      nama_pasien: formData.get('nama_pasien'),
      kelas_pasien: formData.get('kelas_pasien'),
      complaint_category_id: formData.get('complaint_category_id') || null,
      keluhan: formData.get('keluhan'),
      medicine_id: formData.get('medicine_id') || null,
      jumlah_obat: formData.get('jumlah_obat') ? parseInt(formData.get('jumlah_obat'), 10) : null,
      tindakan: formData.get('tindakan'),
      status: formData.get('status')
    };

    try {
      const res = await fetch('/medical-records', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Accept: 'application/json',
          'X-CSRF-TOKEN': getCsrfToken()
        },
        body: JSON.stringify(payload)
      });
      const data = await res.json();
      if (res.ok) {
        sick.unshift(data.data);
        if (payload.medicine_id && payload.jumlah_obat) {
          const med = meds.find((m) => m.id == payload.medicine_id);
          if (med) {
            med.stok = Math.max(0, med.stok - payload.jumlah_obat);
          }
        }
        form.reset();
        render();
        showToast(`Rekam medis siswa ${payload.nama_pasien} berhasil disimpan.`, 'success');
      } else {
        showToast(data.message || 'Gagal menyimpan data siswa.', 'error');
      }
    } catch (err) {
      showToast('Gagal menghubungi server.', 'error');
    }
  }
});

const cxBtn = $('#cx');
if (cxBtn) {
  cxBtn.onclick = () => $('#lm').classList.remove('on');
}

const goBtn = $('#go');
if (goBtn) {
  goBtn.onclick = async () => {
    const u = $('#u').value.trim();
    const p = $('#p').value;
    const er = $('#er');

    if (!u || !p) {
      er.textContent = 'Harap isi nama pengguna dan kata sandi.';
      return;
    }

    er.textContent = 'Memproses...';

    try {
      const res = await fetch('/login', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Accept: 'application/json',
          'X-CSRF-TOKEN': getCsrfToken()
        },
        body: JSON.stringify({ login: u, password: p })
      });

      const data = await res.json();

      if (res.ok && data.success) {
        er.textContent = '';
        $('#lm').classList.remove('on');
        $('#u').value = $('#p').value = '';

        if (data.user.role === 'admin') {
          window.location.href = '/admin';
          return;
        }

        currentUser = data.user;
        $('#site').style.display = 'none';
        $('#app').classList.add('on');
        const roleLabel =
          currentUser.role === 'guru_uks' ? 'Petugas / Guru UKS' : 'Pihak Sekolah (Monitoring)';
        const roleClass = currentUser.role === 'guru_uks' ? 'role-guru' : 'role-sekolah';
        $('#who').innerHTML = `Masuk sebagai: <b>${esc(currentUser.name)}</b> <span class="role-badge ${roleClass}">${roleLabel}</span>`;
        $('#navLogin').style.display = 'none';
        cur = 'stok';
        await loadData();
        window.scrollTo(0, 0);
      } else {
        er.textContent = data.message || 'Nama pengguna atau kata sandi salah.';
      }
    } catch (err) {
      er.textContent = 'Gagal menghubungi server.';
    }
  };
}

const pInput = $('#p');
if (pInput) {
  pInput.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') $('#go').click();
  });
}

const outBtn = $('#out');
if (outBtn) {
  outBtn.onclick = async () => {
    try {
      await fetch('/logout', {
        method: 'POST',
        headers: {
          Accept: 'application/json',
          'X-CSRF-TOKEN': getCsrfToken()
        }
      });
    } catch (e) {}

    currentUser = null;
    $('#app').classList.remove('on');
    $('#site').style.display = '';
    $('#navLogin').style.display = '';
    $('#u').value = $('#p').value = '';
    const toAdmin = $('#btnToAdmin');
    if (toAdmin) toAdmin.remove();
  };
}

window.closeDispensasiModal = function () {
  const m = document.getElementById('dispensasiModal');
  if (m) m.classList.remove('on');
};

window.printDispensasiLetter = function (id) {
  const s = sick.find((x) => x.id === id);
  if (!s) return;
  const now = new Date();
  const romanMonths = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
  const monthNames = [
    'Januari',
    'Februari',
    'Maret',
    'April',
    'Mei',
    'Juni',
    'Juli',
    'Agustus',
    'September',
    'Oktober',
    'November',
    'Desember'
  ];
  const dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

  let recDate = now;
  if (s.created_at) {
    const parsed = new Date(s.created_at);
    if (!isNaN(parsed.getTime())) recDate = parsed;
  }

  const dayName = dayNames[recDate.getDay()];
  const dateFormatted = `${recDate.getDate()} ${monthNames[recDate.getMonth()]} ${recDate.getFullYear()}`;
  const timeFormatted = `${String(recDate.getHours()).padStart(2, '0')}:${String(recDate.getMinutes()).padStart(2, '0')} WIB`;
  const romanMonth = romanMonths[recDate.getMonth()];
  const year = recDate.getFullYear();
  const docNo = `421.5 / UKS-SMKN5 / ${romanMonth} / ${year} / ${String(s.id).padStart(3, '0')}`;

  let statusText = 'ISTIRAHAT DI RUANG UKS';
  let rekomendasi =
    'Siswa yang bersangkutan dalam kondisi sakit dan membutuhkan waktu istirahat di bawah pengawasan petugas UKS, sehingga diberikan izin dispensasi sementara dari kegiatan belajar mengajar (KBM).';
  let statusColor = '#0f766e';

  if (s.status === 'dirujuk') {
    statusText = 'DIRUJUK KE FASILITAS KESEHATAN (DARURAT / PARAH)';
    rekomendasi =
      'Berdasarkan hasil pemeriksaan medis awal di ruang UKS, kondisi siswa memerlukan penanganan medis lanjutan dan segera <b>DIRUJUK ke Fasilitas Kesehatan (Puskesmas / Rumah Sakit)</b>. Siswa diberikan izin/dispensasi penuh meninggalkan lingkungan sekolah didampingi petugas atau keluarga.';
    statusColor = '#b91c1c';
  } else if (s.status === 'sedang_dirawat') {
    statusText = 'PERAWATAN INTENSIF UKS / IZIN PULANG';
    rekomendasi =
      'Siswa mengalami sakit yang memerlukan penanganan istirahat baring intensif di ruang UKS atau disarankan untuk dipulangkan ke rumah di bawah pengawasan orang tua/wali. Siswa diberikan dispensasi tidak mengikuti kegiatan belajar mengajar (KBM) hari ini.';
    statusColor = '#b45309';
  } else if (s.status === 'selesai') {
    statusText = 'SELESAI DITANGANI (KEMBALI KE KELAS)';
    rekomendasi =
      'Siswa telah diberikan penanganan medis awal di ruang UKS dan kondisi telah membaik untuk melanjutkan kegiatan belajar mengajar (KBM).';
  }

  const html = `
    <div class="dispensasi-letter" id="printableLetter">
      <div class="letter-kop">
        <div class="kop-logo-left">
          <img src="/logo.png" alt="Logo" class="kop-logo-img">
        </div>
        <div class="kop-text">
          <h3>PEMERINTAH ACEH &bull; DINAS PENDIDIKAN</h3>
          <h2>SMK NEGERI 5 TELKOM BANDA ACEH</h2>
          <h4>UNIT KESEHATAN SEKOLAH (UKS) MEDISCA</h4>
          <p>Jalan Teuku Nyak Arief No. 222, Syiah Kuala, Kota Banda Aceh, Aceh 23115</p>
          <p>Telepon: (0651) 7552314 | Pos-el: smkn5telkombandaaceh@gmail.com</p>
        </div>
      </div>
      <div class="kop-separator"></div>

      <div class="letter-header">
        <h3 class="letter-title">SURAT KETERANGAN DISPENSASI SAKIT SISWA</h3>
        <p class="letter-number">Nomor: ${docNo}</p>
      </div>

      <div class="letter-body">
        <p>Yang bertanda tangan di bawah ini, Petugas Unit Kesehatan Sekolah (UKS) SMK Negeri 5 Telkom Banda Aceh menerangkan dengan sebenarnya bahwa:</p>
        
        <table class="letter-data-table">
          <tr>
            <td style="width: 170px;">Nama Siswa</td>
            <td style="width: 15px;">:</td>
            <td><b>${esc(s.nama_pasien)}</b></td>
          </tr>
          <tr>
            <td>Kelas / Jurusan</td>
            <td>:</td>
            <td><b>${esc(s.kelas_pasien || '-')}</b></td>
          </tr>
          <tr>
            <td>Waktu Pemeriksaan</td>
            <td>:</td>
            <td>${dayName}, ${dateFormatted} pukul ${timeFormatted}</td>
          </tr>
          <tr>
            <td>Keluhan Kesehatan</td>
            <td>:</td>
            <td>${esc(s.keluhan)}</td>
          </tr>
          <tr>
            <td>Obat Diberikan</td>
            <td>:</td>
            <td>${esc(s.medicine ? s.medicine.nama_obat : 'Tanpa pemberian obat')} ${s.jumlah_obat ? '(' + s.jumlah_obat + ')' : ''}</td>
          </tr>
          <tr>
            <td>Tindakan Medis UKS</td>
            <td>:</td>
            <td>${esc(s.tindakan || 'Pemeriksaan tanda vital dan observasi')}</td>
          </tr>
          <tr>
            <td>Status / Rekomendasi</td>
            <td>:</td>
            <td><span class="letter-status-badge" style="background:${statusColor}15; color:${statusColor}; border:1px solid ${statusColor}40;">${statusText}</span></td>
          </tr>
        </table>

        <div class="letter-notes">
          <p><b>Keterangan / Tindakan Lanjutan:</b></p>
          <p>${rekomendasi}</p>
        </div>

        <p class="letter-closing">Demikian surat keterangan ini diterbitkan dengan sebenar-benarnya untuk dipergunakan sebagai bukti dispensasi kehadiran pada Kegiatan Belajar Mengajar (KBM) dan koordinasi bersama Guru Piket / Wali Kelas.</p>
      </div>

      <div class="letter-signatures">
        <div class="sig-col">
          <p>Mengetahui,</p>
          <p class="sig-role">Guru Piket Sekolah</p>
          <div class="sig-space"></div>
          <p class="sig-name">( .................................................... )</p>
          <p class="sig-nip">NIP. -</p>
        </div>
        <div class="sig-col">
          <p>Banda Aceh, ${dateFormatted}</p>
          <p class="sig-role">Petugas / Pembina UKS Medisca</p>
          <div class="sig-space"></div>
          <p class="sig-name"><b>${esc(currentUser ? currentUser.name : 'Petugas Medisca')}</b></p>
          <p class="sig-nip">Petugas Layanan Kesehatan Sekolah</p>
        </div>
      </div>
    </div>
  `;

  const container = document.getElementById('printableLetterContainer');
  if (container) container.innerHTML = html;
  const modal = document.getElementById('dispensasiModal');
  if (modal) modal.classList.add('on');
};

(function () {
  const R = ['stok', 'sakit', 'gej', 'atur', 'lap', 'edu'];
  function openApp(t) {
    if (currentUser) {
      $('#lm').classList.remove('on');
      $('#site').style.display = 'none';
      $('#app').classList.add('on');
      $('#navLogin').style.display = 'none';
      cur = t;
      render();
      window.scrollTo(0, 0);
    }
  }

  function route() {
    const h = location.hash.slice(1);
    if (R.includes(h)) openApp(h);
    else if (h === 'beranda' && $('#app').classList.contains('on')) $('#out').click();
  }

  window.addEventListener('hashchange', route);
  function init() {
    setupSplash();
    setupPwa();
    checkAuthStatus();
    route();
  }
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
