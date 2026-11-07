let reports = [];
let currentFilter = "all";
let activeReportId = null;

const getCsrfToken = () => {
  const meta = document.querySelector('meta[name="csrf-token"]');
  return meta ? meta.getAttribute('content') : '';
};

document.addEventListener("DOMContentLoaded", () => {
  fetchReports();
  setupAdminLogout();
});

async function fetchReports() {
  try {
    const res = await fetch('/admin/reports', {
      headers: {
        'Accept': 'application/json'
      }
    });
    if (res.ok) {
      const json = await res.json();
      reports = json.data || [];
      updateCounts(json.counts);
      renderReports();
    }
  } catch (err) {
  }
}

function updateCounts(counts) {
  const bugCount = counts ? counts.bug : reports.filter(r => r.category === "bug" && r.status === "pending").length;
  const errorCount = counts ? counts.error : reports.filter(r => r.category === "error" && r.status === "pending").length;
  const suspiciousCount = counts ? counts.suspicious : reports.filter(r => r.category === "mencurigakan" && r.status === "pending").length;
  const totalCount = counts ? counts.total : reports.length;

  const countBugEl = document.getElementById("count-bug");
  const countErrorEl = document.getElementById("count-error");
  const countSuspiciousEl = document.getElementById("count-suspicious");
  const totalCountEl = document.getElementById("total-count");

  if (countBugEl) countBugEl.innerText = bugCount;
  if (countErrorEl) countErrorEl.innerText = errorCount;
  if (countSuspiciousEl) countSuspiciousEl.innerText = suspiciousCount;
  if (totalCountEl) totalCountEl.innerText = totalCount;
}

function renderReports() {
  const tbody = document.getElementById("reportTableBody");
  const emptyState = document.getElementById("emptyState");
  const searchInput = document.getElementById("searchInput");
  const keyword = searchInput ? searchInput.value.toLowerCase().trim() : "";

  const filtered = reports.filter(item => {
    const matchCategory = (currentFilter === "all") || (item.category === currentFilter);
    const matchSearch = item.title.toLowerCase().includes(keyword) || 
                        item.page.toLowerCase().includes(keyword) ||
                        (item.category_label || item.categoryLabel || "").toLowerCase().includes(keyword);
    return matchCategory && matchSearch;
  });

  if (!tbody) return;

  if (filtered.length === 0) {
    tbody.innerHTML = "";
    if (emptyState) emptyState.style.display = "block";
    return;
  }

  if (emptyState) emptyState.style.display = "none";
  tbody.innerHTML = filtered.map(item => `
    <tr>
      <td style="color: #64748b; font-size: 0.8rem;">${item.created_at ? item.created_at.substring(0, 16).replace('T', ' ') : (item.time || '-')}</td>
      <td>
        <span class="badge badge-${item.category}">${item.category_label || item.categoryLabel}</span>
      </td>
      <td>
        <strong>${item.title}</strong>
      </td>
      <td>
        <span class="page-code">${item.page}</span>
      </td>
      <td>
        <span class="status-badge ${item.status === 'done' ? 'status-done' : 'status-pending'}">
          ${item.status === 'done' ? '✓ ' + (item.status_label || item.statusLabel || 'Selesai') : '● ' + (item.status_label || item.statusLabel || 'Perlu Ditinjau')}
        </span>
      </td>
      <td style="text-align: right;">
        <button class="btn btn-secondary btn-sm" onclick="openDetail(${item.id})">Lihat Detail</button>
      </td>
    </tr>
  `).join("");
}

function filterCategory(cat) {
  currentFilter = cat;

  document.querySelectorAll(".tab-filters .tab-btn").forEach(btn => {
    if (btn.getAttribute("data-filter") === cat) {
      btn.classList.add("active");
    } else {
      btn.classList.remove("active");
    }
  });

  renderReports();
}

function filterItems() {
  renderReports();
}

function openDetail(id) {
  const item = reports.find(r => r.id === id);
  if (!item) return;

  activeReportId = id;

  const modalTitle = document.getElementById("modalTitle");
  const modalCategory = document.getElementById("modalCategory");
  const modalPage = document.getElementById("modalPage");
  const modalTime = document.getElementById("modalTime");
  const modalDesc = document.getElementById("modalDesc");
  const modalSolution = document.getElementById("modalSolution");
  const btnToggle = document.getElementById("btnToggleStatus");

  if (modalTitle) modalTitle.innerText = item.title;
  if (modalCategory) {
    modalCategory.innerText = item.category_label || item.categoryLabel;
    modalCategory.className = `badge badge-${item.category}`;
  }
  if (modalPage) modalPage.innerText = item.page;
  if (modalTime) modalTime.innerText = item.created_at ? item.created_at.substring(0, 16).replace('T', ' ') : (item.time || '-');
  if (modalDesc) modalDesc.innerText = item.desc;
  if (modalSolution) modalSolution.innerText = item.solution;

  if (btnToggle) {
    if (item.status === "done") {
      btnToggle.innerText = "Buka Kembali Masalah";
      btnToggle.className = "btn btn-secondary";
    } else {
      btnToggle.innerText = "Tandai Selesai";
      btnToggle.className = "btn btn-primary";
    }
  }

  const detailModal = document.getElementById("detailModal");
  if (detailModal) detailModal.classList.add("show");
}

function closeModal() {
  const detailModal = document.getElementById("detailModal");
  if (detailModal) detailModal.classList.remove("show");
  activeReportId = null;
}

async function toggleCurrentStatus() {
  if (!activeReportId) return;

  const item = reports.find(r => r.id === activeReportId);
  if (!item) return;

  const newStatus = item.status === "pending" ? "done" : "pending";

  try {
    const res = await fetch(`/admin/reports/${activeReportId}/status`, {
      method: 'PATCH',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': getCsrfToken()
      },
      body: JSON.stringify({ status: newStatus })
    });

    if (res.ok) {
      const data = await res.json();
      item.status = newStatus;
      item.status_label = newStatus === 'done' ? 'Selesai' : 'Perlu Ditinjau';
      closeModal();
      await fetchReports();
    } else {
      alert('Gagal mengubah status laporan.');
    }
  } catch (err) {
    alert('Terjadi kesalahan jaringan.');
  }
}

function setupAdminLogout() {
  const logoutBtn = document.getElementById("adminLogout");
  if (logoutBtn) {
    logoutBtn.addEventListener("click", async (e) => {
      e.preventDefault();
      try {
        await fetch('/logout', {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': getCsrfToken()
          }
        });
      } catch (err) {}
      window.location.href = '/';
    });
  }
}
