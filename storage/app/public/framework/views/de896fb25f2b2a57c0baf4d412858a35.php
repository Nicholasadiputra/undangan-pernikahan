<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
  <title><?php echo e($landing->groom_name ?? 'Groom'); ?> & <?php echo e($landing->bride_name ?? 'Bride'); ?> — Data Tamu</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="<?php echo e(asset('css/dataTamu.css')); ?>"/>
  <script src="<?php echo e(asset('js/dataTamu.js')); ?>" defer></script>
  <link rel="icon" type="image/jpg" href="<?php echo e(asset('favicon.jpg')); ?>">
</head>
<body>

<button class="hamburger" id="hamburger" aria-label="Menu">
  <span></span><span></span><span></span>
</button>
<div class="overlay" id="overlay"></div>

<?php echo $__env->make('dashboard.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<main class="main">
  <h1 class="page-title">Data Tamu</h1>

  <div class="table-card">
    <div class="toolbar">
      <div class="search-wrap">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
        </svg>
        <input type="text" id="searchInput" placeholder="Cari Nama Tamu…" />
      </div>
      <button class="btn btn-outline" onclick="document.getElementById('importExcelInput').click()">Import Excel</button>
      <input type="file" id="importExcelInput" accept=".xlsx,.xls" style="display:none" onchange="handleImportFile(event)" />
      <button class="btn btn-outline" onclick="downloadExcel()">Download Excel</button>
      <button class="btn btn-primary" onclick="openModal()">Tambah Tamu</button>
    </div>

    <div class="table-wrap">
      <table id="tamuTable">
        <thead>
          <tr>
            <th>Nama Tamu</th>
            <th>Kategori</th>
            <th>Pax</th>
            <th>Status</th>
            <th>Ucapan</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody id="tamuBody"></tbody>
      </table>
    </div>
    <div class="pagination" id="pagination"></div>
  </div>
</main>

<!-- Modal Tambah/Edit Tamu -->
<div class="modal-backdrop" id="modalBackdrop">
  <div class="modal">
    <h2 id="modalTitle">Tambah Tamu</h2>
    <div class="form-group">
      <label>Nama Tamu</label>
      <input type="text" id="fNama" placeholder="Contoh: Andi Saputra" />
    </div>
    <!-- GANTI bagian select Kategori -->
    <div class="form-group">
      <label>Kategori</label>
      <select id="fKategori" onchange="autoSetPax(this.value)">
        <option value="Family">Family</option>
        <option value="Friend">Friend</option>
      </select>
    </div>

    <div class="form-group" id="paxGroup">
      <label>Pax</label>
      <input type="number" id="fPax" readonly />
    </div>

    <div class="form-group">
      <label>Status</label>
      <select id="fStatus" onchange="togglePaxByStatus(this.value)">
        <option value="Hadir">Hadir</option>
        <option value="Tidak Hadir">Tidak Hadir</option>
      </select>
    </div>
    <div class="form-group">
      <label>Ucapan</label>
      <input type="text" id="fUcapan" placeholder="Ucapan dari tamu" />
    </div>
    <div class="modal-actions">
      <button class="btn btn-outline" onclick="closeModal()">Batal</button>
      <button class="btn btn-primary" onclick="saveTamu()">Simpan</button>
    </div>
  </div>
</div>
</body>
</html><?php /**PATH C:\laragon\www\undangan\resources\views/dashboard/dataTamu.blade.php ENDPATH**/ ?>