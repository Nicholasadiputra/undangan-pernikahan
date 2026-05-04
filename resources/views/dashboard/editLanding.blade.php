<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Nicholas & Nahda — Edit Landing Page</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="{{ asset('css/editLanding.css') }}"/>
  <script src="{{ asset('js/editLanding.js') }}" defer></script>
</head>
<body>

<button class="hamburger" id="hamburger" aria-label="Menu">
  <span></span><span></span><span></span>
</button>
<div class="overlay" id="overlay"></div>

@include('dashboard.partials.sidebar')

<main class="main">
  <h1 class="page-title">Edit Landing Page</h1>

  {{-- Notifikasi Sukses --}}
  @if(session('success'))
      <div style="padding: 12px; background-color: #d4edda; color: #155724; border-radius: 4px; margin-bottom: 20px;">
          {{ session('success') }}
      </div>
  @endif

  {{-- Mulai Form Laravel --}}
  <form action="{{ route('landing.update') }}" method="POST" enctype="multipart/form-data" id="landingForm">
    @csrf

    <div class="content-grid">

      <!-- INFORMASI ACARA -->
      <div class="card full-col">
        <div class="card-header" style="margin-bottom:15px;">
          <h2>Informasi Mempelai & Acara</h2>
        </div>

        <div class="setting-row" style="display: block; margin-bottom: 15px;">
          <label style="display: block; margin-bottom: 5px; font-weight: 500;">Nama Mempelai Pria</label>
          <input type="text" name="groom_name" value="{{ old('groom_name', $landing->groom_name ?? '') }}" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;" placeholder="Contoh: Nicholas">
        </div>

        <div class="setting-row" style="display: block; margin-bottom: 15px;">
          <label style="display: block; margin-bottom: 5px; font-weight: 500;">Nama Mempelai Wanita</label>
          <input type="text" name="bride_name" value="{{ old('bride_name', $landing->bride_name ?? '') }}" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;" placeholder="Contoh: Nahda">
        </div>

        <div class="setting-row" style="display: block; margin-bottom: 15px;">
          <label style="display: block; margin-bottom: 5px; font-weight: 500;">Tanggal Pernikahan</label>
          <input type="date" name="wedding_date" value="{{ old('wedding_date', $landing->wedding_date ?? '') }}" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
        </div>
      </div>

      <!-- PILIH TAMPILAN (full width) -->
      <div class="card full-col">
        <div class="card-header">
          <h2>Pilih Tampilan</h2>
          {{-- Ubah button menjadi type="submit" dan hapus onclick="handleSave()" agar form disubmit via Laravel --}}
          <button type="submit" class="btn-save">SAVE</button>
        </div>

        <p class="section-label">Pilih Template</p>
        
        {{-- Input hidden untuk menyimpan data template yang dipilih via JavaScript --}}
        <input type="hidden" name="template" id="selected_template" value="{{ old('template', $landing->template ?? 'bohemian') }}">

        <div class="template-grid">
          <div class="template-item selected" id="tpl-bohemian" onclick="selectTemplate('bohemian')">
            <div class="template-placeholder" style="background:linear-gradient(135deg,#c9a96e 0%,#7a5230 100%);color:#fff8f0;font-size:.85rem;font-weight:600;">foto1</div>
            <div class="template-info">
              <div class="template-name">Bohemian</div>
              <div class="template-sub">Warm — Earth</div>
            </div>
          </div>
          <div class="template-item" id="tpl-modern" onclick="selectTemplate('modern')">
            <div class="template-placeholder" style="background:linear-gradient(135deg,#e8e0d4 0%,#bfb4a6 100%);color:#6b5b4e;font-size:.85rem;font-weight:600;">foto2</div>
            <div class="template-info">
              <div class="template-name">Modern</div>
              <div class="template-sub">Clean — Minimalis</div>
            </div>
          </div>
        </div>

        <div class="atau-divider">ATAU</div>

        <p class="section-label">Upload Custom</p>
        <div class="upload-grid">
          <label class="upload-box">
            {{-- Tambahkan atribut name="hero_image" --}}
            <input type="file" name="hero_image" accept="image/*" onchange="handleUpload(this,'thumbnail')"/>
            <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/>
              <polyline points="21 15 16 10 5 21"/>
            </svg>
            <span id="thumb-label">Upload Thumbnail</span>
            <small>JPG, PNG, SVG — Max 2MB</small>
          </label>
          <label class="upload-box">
            {{-- Tambahkan atribut name="custom_html" --}}
            <input type="file" name="custom_html" accept=".html" onchange="handleUpload(this,'html')"/>
            <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
              <polyline points="14 2 14 8 20 8"/>
            </svg>
            <span id="html-label">Upload file HTML</span>
            <small>Hanya HTML — Max 2MB</small>
          </label>
        </div>
      </div>

      <!-- PENGATURAN HALAMAN -->
      <div class="card">
        <div class="card-header" style="margin-bottom:6px;">
          <h2>Pengaturan Halaman</h2>
        </div>

        <div class="setting-row">
          <div class="setting-info">
            <div class="setting-title">Tampilkan Animasi</div>
            <div class="setting-sub">Fade in saat halaman dibuka</div>
          </div>
          <label class="toggle">
            {{-- Tambahkan atribut name dan value checked dinamis --}}
            <input type="checkbox" name="show_animation" value="1" id="tog-animasi" {{ old('show_animation', $landing->show_animation ?? true) ? 'checked' : '' }}/>
            <span class="slider"></span>
          </label>
        </div>
        <div class="setting-row">
          <div class="setting-info">
            <div class="setting-title">Musik Latar</div>
            <div class="setting-sub">Putar otomatis saat masuk</div>
          </div>
          <label class="toggle">
            <input type="checkbox" name="play_music" value="1" id="tog-musik" {{ old('play_music', $landing->play_music ?? true) ? 'checked' : '' }}/>
            <span class="slider"></span>
          </label>
        </div>
        <div class="setting-row">
          <div class="setting-info">
            <div class="setting-title">Nama Tamu Saat Masuk</div>
            <div class="setting-sub">Tampilkan "Hey, [Nama]"</div>
          </div>
          <label class="toggle">
            <input type="checkbox" name="show_guest_name" value="1" id="tog-nama" {{ old('show_guest_name', $landing->show_guest_name ?? true) ? 'checked' : '' }}/>
            <span class="slider"></span>
          </label>
        </div>
        <div class="setting-row">
          <div class="setting-info">
            <div class="setting-title">Mode Privat</div>
            <div class="setting-sub">Hanya bisa diakses via link</div>
          </div>
          <label class="toggle">
            <input type="checkbox" name="is_private" value="1" id="tog-privat" {{ old('is_private', $landing->is_private ?? false) ? 'checked' : '' }}/>
            <span class="slider"></span>
          </label>
        </div>
      </div>

      <!-- REVIEW AKTIF -->
      <div class="card">
        <div class="review-header">
          <h2>Review Aktif</h2>
          <div class="live-badge"><span class="live-dot"></span> Live</div>
        </div>

        <div class="review-preview">
          <div class="review-preview-placeholder">
            <span class="preview-overlay-text" id="previewLabel">{{ ucfirst($landing->template ?? 'Bohemian') }} Template</span>
          </div>
        </div>

        <div class="review-stats">
          <div>
            <div class="rs-val" id="rv-tamu">100</div>
            <div class="rs-lbl">Tamu</div>
          </div>
          <div>
            <div class="rs-val" id="rv-dibuka">83</div>
            <div class="rs-lbl">Dibuka</div>
          </div>
          <div>
            <div class="rs-val" id="rv-rate">83%</div>
            <div class="rs-lbl">Open Rate</div>
          </div>
        </div>
      </div>

    </div><!-- end content-grid -->
  </form>
  {{-- Akhir Form Laravel --}}

</main>

{{-- Tambahan Script untuk memanipulasi input hidden template saat diklik --}}
<script>
  function selectTemplate(templateName) {
      // Mengubah value input hidden
      document.getElementById('selected_template').value = templateName;
      // Mengubah label preview
      document.getElementById('previewLabel').innerText = templateName.charAt(0).toUpperCase() + templateName.slice(1) + ' Template';
      
      // Logika CSS active state (asumsi Anda sudah ada di editLanding.js, jika belum tambahkan ini)
      document.querySelectorAll('.template-item').forEach(item => item.classList.remove('selected'));
      document.getElementById('tpl-' + templateName).classList.add('selected');
  }
</script>
</body>
</html>