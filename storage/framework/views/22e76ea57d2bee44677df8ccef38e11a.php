<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Nicholas & Nahda — Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="<?php echo e(asset('css/dashboard.css')); ?>"/> 
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="<?php echo e(asset('js/dashboard.js')); ?>" defer></script>
    <link rel="icon" type="image/jpg" href="<?php echo e(asset('favicon.jpg')); ?>">
</head>
<body>

<button class="hamburger" id="hamburger" aria-label="Menu">
  <span></span><span></span><span></span>
</button>
<div class="overlay" id="overlay"></div>

<?php echo $__env->make('dashboard.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<main class="main">
  <h1 class="page-title">Dashboard</h1>

  <!-- Stat Cards -->
  <div class="stats-row">
    <div class="stat-card">
      <div class="stat-info">
        <span class="stat-label">Total Tamu Terdaftar</span>
        <span class="stat-value" id="totalTamu"><?php echo e($total); ?></span>
      </div>
      <div class="stat-icon blue">
        <svg width="26" height="26" fill="none" stroke="#5b7ac4" stroke-width="2" viewBox="0 0 24 24">
          <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
          <circle cx="9" cy="7" r="4"/>
          <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
        </svg>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-info">
        <span class="stat-label">Konfirmasi Hadir</span>
        <span class="stat-value" id="konfHadir"><?php echo e($hadir); ?></span>
      </div>
      <div class="stat-icon green">
        <svg width="26" height="26" fill="none" stroke="#3a9e6a" stroke-width="2" viewBox="0 0 24 24">
          <circle cx="12" cy="12" r="10"/>
          <path d="M9 12l2 2 4-4"/>
        </svg>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-info">
        <span class="stat-label">Konfirmasi Tidak Hadir</span>
        <span class="stat-value" id="konfTidak"><?php echo e($tidakHadir); ?></span>
      </div>
      <div class="stat-icon red">
        <svg width="26" height="26" fill="none" stroke="#e05a52" stroke-width="2" viewBox="0 0 24 24">
          <circle cx="12" cy="12" r="10"/>
          <path d="M12 6v6l4 2"/>
        </svg>
      </div>
    </div>
  </div>

  <!-- Bottom row: menunggu + donut -->
  <div class="bottom-row">
    <div class="stat-card waiting">
      <div class="stat-info">
        <span class="stat-label">Menunggu Respon</span>
        <span class="stat-value" id="menunggu"><?php echo e($menunggu); ?></span>
      </div>
      <div class="stat-icon orange">
        <svg width="26" height="26" fill="none" stroke="#b36200" stroke-width="2" viewBox="0 0 24 24">
          <circle cx="12" cy="12" r="10"/>
          <path d="M12 6v6l4 2"/>
        </svg>
      </div>
    </div>

    <div class="donut-card">
      <div class="donut-wrap">
        <canvas id="donutChart" width="140" height="140"></canvas>
        <div class="donut-label">
          <div class="dnum" id="donutTotal"><?php echo e($total); ?></div>
          <div class="dtxt">Tamu Terdaftar</div>
        </div>
      </div>
      <div class="donut-legend">
        <div class="legend-item">
          <span class="legend-dot green" id="pctHadir"><?php echo e($total > 0 ? round($hadir / $total * 100) : 0); ?>%</span>
          <span>Konfirmasi Hadir</span>
        </div>
        <div class="legend-item">
          <span class="legend-dot red" id="pctTidak"><?php echo e($total > 0 ? round($tidakHadir / $total * 100) : 0); ?>%</span>
          <span>Konfirmasi Tidak Hadir</span>
        </div>
        <div class="legend-item">
          <span class="legend-dot orange" id="pctMenunggu"><?php echo e($total > 0 ? round($menunggu / $total * 100) : 0); ?>%</span>
          <span>Menunggu Respon</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Countdown -->
  <div class="countdown-card">
    <div class="countdown-title">Waktu Tersisa Menuju Hari H</div>
    <div class="countdown-digits">
      <div class="cd-unit">
        <span class="cd-num" id="cdDays">000</span>
        <span class="cd-lbl">Days</span>
      </div>
      <span class="cd-sep">:</span>
      <div class="cd-unit">
        <span class="cd-num" id="cdHours">00</span>
        <span class="cd-lbl">Hours</span>
      </div>
      <span class="cd-sep">:</span>
      <div class="cd-unit">
        <span class="cd-num" id="cdMinutes">00</span>
        <span class="cd-lbl">Minutes</span>
      </div>
      <span class="cd-sep">:</span>
      <div class="cd-unit">
        <span class="cd-num" id="cdSeconds">00</span>
        <span class="cd-lbl">Seconds</span>
      </div>
    </div>
  </div>
</main>

<script>
  // Pass wedding date to JS
  const weddingDate = '<?php echo e($landing->wedding_date ?? null); ?>';
</script>
</body>
</html><?php /**PATH C:\laragon\www\undangan-pernikahan\resources\views/dashboard/index.blade.php ENDPATH**/ ?>