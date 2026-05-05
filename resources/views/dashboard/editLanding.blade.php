<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Edit Landing Page — {{ $landing->groom_name ?? "Groom" }} &amp; {{ $landing->bride_name ?? "Bride" }}</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="{{ asset('css/editLanding.css')}}"/> 
  <link rel="stylesheet" href="{{ asset('css/dataAdmin.css')}}"/>
  <script src="{{ asset('js/editLanding.js')}}" defer></script>
  <link rel="icon" type="image/jpg" href="{{ asset('favicon.jpg') }}">
</head>
<body>

<button class="hamburger" id="hamburger" aria-label="Menu">
  <span></span><span></span><span></span>
</button>
<div class="overlay" id="overlay"></div>

@include('dashboard.partials.sidebar')

<main class="main">
  <div class="page-header">
    <h1 class="page-title">Edit Landing</h1>
    <button type="submit" form="landingForm" id="saveBtn" class="btn btn-primary">SAVE</button>
  </div>

  @if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  
  <div class="content-area">

    <form method="POST" action="{{ route('landing.update') }}" enctype="multipart/form-data" id="landingForm">
    @csrf

    {{-- ═══ CARD 1: Informasi Mempelai ═══ --}}
    <div class="card">
      <div class="card-head">
        <span>Informasi Mempelai</span>
      </div>
      <div class="card-content">

        <div class="form-row">
          <div>
            <label class="lbl">Mempelai Pria</label>
            <input type="text" name="groom_name" class="inp" value="{{ old('groom_name', $landing->groom_name) }}" placeholder="Nama mempelai pria" />
          </div>
          <div>
            <label class="lbl">Mempelai Wanita</label>
            <input type="text" name="bride_name" class="inp" value="{{ old('bride_name', $landing->bride_name) }}" placeholder="Nama mempelai wanita" />
          </div>
        </div>

        <div class="divider-label">Orang Tua</div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="lbl">Ayah Mempelai Pria</label>
            <input type="text" name="ayah_pria" class="inp" value="{{ old('ayah_pria', $landing->ayah_pria) }}" placeholder="Nama ayah mempelai pria" />
          </div>
          <div>
            <label class="lbl">Ayah Mempelai Wanita</label>
            <input type="text" name="ayah_wanita" class="inp" value="{{ old('ayah_wanita', $landing->ayah_wanita) }}" placeholder="Nama ayah mempelai wanita" />
          </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="lbl">Ibu Mempelai Pria</label>
            <input type="text" name="ibu_pria" class="inp" value="{{ old('ibu_pria', $landing->ibu_pria) }}" placeholder="Nama ibu mempelai pria" />
          </div>
          <div>
            <label class="lbl">Ibu Mempelai Wanita</label>
            <input type="text" name="ibu_wanita" class="inp" value="{{ old('ibu_wanita', $landing->ibu_wanita) }}" placeholder="Nama ibu mempelai wanita" />
          </div>
        </div>

        <div class="divider-label">Detail Acara</div>

        <div class="grid grid-cols-3 gap-4">
          <div>
            <label class="lbl">Tanggal</label>
            <div class="inp-icon-wrap">
              <svg class="inp-icon w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <rect x="3" y="4" width="18" height="18" rx="2"/>
                <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/>
                <line x1="3" y1="10" x2="21" y2="10"/>
              </svg>
              <input type="date" name="wedding_date" class="inp" value="{{ old('wedding_date', $landing->wedding_date ? \Carbon\Carbon::parse($landing->wedding_date)->format('Y-m-d') : '') }}" />
            </div>
          </div>
          <div>
            <label class="lbl">Lokasi / Gedung</label>
            <input type="text" name="lokasi_wedding" class="inp" value="{{ old('lokasi_wedding', $landing->lokasi_wedding) }}" placeholder="Nama gedung / tempat" />
          </div>
          <div>
            <label class="lbl">Kota</label>
            <input type="text" name="kota" class="inp" value="{{ old('kota', $landing->kota) }}" placeholder="Kota pelaksanaan" />
          </div>
        </div>

        <div>
          <label class="lbl-dark">Google Maps Embed (iframe)</label>
          <textarea name="map_iframe" class="inp" placeholder='<iframe src="https://www.google.com/maps/embed?...">' style="min-height:80px;">{{ old('map_iframe', $landing->map_iframe) }}</textarea>
          <p style="font-size:10px;color:#9A7B5C;margin-top:4px;">Salin embed code dari Google Maps → Share → Embed a map</p>
        </div>

        <div class="grid grid-cols-2 gap-4">

          {{-- ACARA --}}
          <div>
            <label class="lbl-dark">Acara</label>
            <div id="kegiatan-list" style="display:flex;flex-direction:column;gap:8px;margin-top:4px;"></div>
            <p style="font-size:10px;color:#9A7B5C;margin-top:8px;">Isi kegiatan, baris berikutnya otomatis muncul</p>
          </div>

          {{-- DRESS CODE --}}
          <div style="display:flex;flex-direction:column;gap:12px;">
            <div>
              <label class="lbl-dark">Dress Code</label>
              <textarea name="dresscode_text" class="inp" placeholder="Deskripsi dress code…">{{ old('dresscode_text', $landing->dresscode_text) }}</textarea>
            </div>
            <div>
              <label class="lbl-dark">Palette Dress Code</label>
              <div id="palette-row" style="display:flex;flex-wrap:wrap;gap:8px;align-items:center;margin-top:4px;"></div>
              <input type="color" id="globalColorPicker" class="hidden-color" onchange="commitColor(this.value)" />
            </div>
          </div>
        </div>

        <div class="divider-label">Ucapan</div>

        <div style="display:flex;flex-direction:column;gap:16px;">
          <div>
            <label class="lbl-dark">Cerita Bertemu</label>
            <textarea name="cerita_bertemu" class="inp" placeholder="Ceritakan bagaimana kalian pertama bertemu…">{{ old('cerita_bertemu', $landing->cerita_bertemu) }}</textarea>
          </div>
          <div>
            <label class="lbl-dark">Cerita Melamar</label>
            <textarea name="cerita_melamar" class="inp" placeholder="Ceritakan momen lamaran…">{{ old('cerita_melamar', $landing->cerita_melamar) }}</textarea>
          </div>
        </div>

        <div class="divider-label">Galeri</div>

        <div>
          <label class="lbl-dark">Galeri</label>
          <p style="font-size:11px;color:#9A7B5C;margin-bottom:8px;">Upload foto lalu pilih penempatannya. Perubahan tersimpan saat kamu tekan <strong>SAVE</strong>.</p>
          <div id="gallery-grid" class="grid grid-cols-4 gap-3"></div>
        </div>

      </div>
    </div>

    {{-- ═══ CARD 2: Pilih Tampilan ═══ --}}
    <div class="card">
      <div class="card-head">Pilih Tampilan</div>
      <div class="card-content">
        <div>
          <label class="lbl-dark">Pilih Template</label>
          <input type="hidden" id="selected_template" name="template" value="{{ old('template', $landing->template ?? 'bohemian') }}" />
          <div class="grid grid-cols-2 gap-4" style="margin-top:8px;">
            <div id="tpl-bohemian" onclick="selectTemplate('bohemian')" class="tpl-card {{ ($landing->template ?? 'bohemian') === 'bohemian' ? 'selected' : '' }}">
              <div class="aspect-video flex items-center justify-center"
                style="background:linear-gradient(135deg,#8B6340 0%,#C4A47C 50%,#4A2C10 100%);">
                <div class="text-center">
                  <div style="font-size:9px;letter-spacing:2px;text-transform:uppercase;color:rgba(255,255,255,.5);margin-bottom:4px;">Preview</div>
                  <div style="font-family:'Playfair Display',serif;font-style:italic;color:#fff;font-size:14px;">Bohemian</div>
                </div>
              </div>
              <div class="p-3 bg-white">
                <p style="font-family:'Playfair Display',serif;font-style:italic;font-size:15px;color:#3D2B1A;">Bohemian</p>
                <p style="font-size:10px;letter-spacing:1.2px;color:#9A7B5C;text-transform:uppercase;margin-top:2px;">Warm — Earth</p>
              </div>
            </div>
            <div id="tpl-modern" onclick="selectTemplate('modern')" class="tpl-card {{ ($landing->template ?? 'bohemian') === 'modern' ? 'selected' : '' }}">
              <div class="aspect-video flex items-center justify-center"
                style="background:linear-gradient(135deg,#E8E0D4 0%,#D4C5B0 50%,#BFB4A6 100%);">
                <div class="text-center">
                  <div style="font-size:9px;letter-spacing:2px;text-transform:uppercase;color:#9A7B5C;margin-bottom:4px;">Preview</div>
                  <div style="font-family:'Playfair Display',serif;font-style:italic;color:#6B5B4E;font-size:14px;">Modern</div>
                </div>
              </div>
              <div class="p-3 bg-white">
                <p style="font-family:'Playfair Display',serif;font-style:italic;font-size:15px;color:#3D2B1A;">Modern</p>
                <p style="font-size:10px;letter-spacing:1.2px;color:#9A7B5C;text-transform:uppercase;margin-top:2px;">Clean — Minimalis</p>
              </div>
            </div>
          </div>
        </div>

        <div class="divider-label">Atau</div>

        <div>
          <label class="lbl-dark">Upload Custom</label>
          <div class="grid grid-cols-2 gap-4" style="margin-top:8px;">
            <label class="upload-box cursor-pointer">
              <input type="file" name="custom_thumbnail" accept="image/*" class="hidden" onchange="handleUploadLabel(this,'thumb-label')" />
              <svg style="width:28px;height:28px;color:#9A7B5C;" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <rect x="3" y="3" width="18" height="18" rx="2"/>
                <circle cx="8.5" cy="8.5" r="1.5"/>
                <polyline points="21 15 16 10 5 21"/>
              </svg>
              <span id="thumb-label" style="font-size:12px;font-weight:500;color:#3D2B1A;">Upload Thumbnail</span>
              <span style="font-size:10px;color:#9A7B5C;">JPG, PNG, SVG — Max 2MB</span>
            </label>
            <label class="upload-box cursor-pointer">
              <input type="file" name="custom_html" accept=".html" class="hidden" onchange="handleUploadLabel(this,'html-label')" />
              <svg style="width:28px;height:28px;color:#9A7B5C;" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
                <line x1="16" y1="13" x2="8" y2="13"/>
                <line x1="16" y1="17" x2="8" y2="17"/>
                <polyline points="10 9 9 9 8 9"/>
              </svg>
              <span id="html-label" style="font-size:12px;font-weight:500;color:#3D2B1A;">Upload file HTML</span>
              <span style="font-size:10px;color:#9A7B5C;">Hanya HTML — Max 2MB</span>
            </label>
          </div>
        </div>
      </div>
    </div>

    <!--
    {{-- ═══ BOTTOM ROW ═══ --}}
    <div class="grid grid-cols-2 gap-5">

      <div class="card">
        <div class="card-head">Pengaturan Halaman</div>
        <div class="settings-content">
          <div class="flex items-center justify-between py-[11px]">
            <div>
              <p style="font-size:13px;font-weight:500;color:#3D2B1A;">Tampilkan Animasi</p>
              <p style="font-size:11px;color:#C4860A;margin-top:2px;">Fade in saat halaman dibuka</p>
            </div>
            <label class="cursor-pointer"><input type="checkbox" id="tog-animasi" name="show_animation" class="tog-input" {{ $landing->show_animation ? 'checked' : '' }} /><div class="tog-track"></div></label>
          </div>
          <div class="flex items-center justify-between py-[11px]">
            <div>
              <p style="font-size:13px;font-weight:500;color:#3D2B1A;">Musik Latar</p>
              <p style="font-size:11px;color:#C4860A;margin-top:2px;">Putar otomatis saat masuk</p>
            </div>
            <label class="cursor-pointer"><input type="checkbox" id="tog-musik" name="play_music" class="tog-input" {{ $landing->play_music ? 'checked' : '' }} /><div class="tog-track"></div></label>
          </div>
          <div class="flex items-center justify-between py-[11px]">
            <div>
              <p style="font-size:13px;font-weight:500;color:#3D2B1A;">Nama Tamu Saat Masuk</p>
              <p style="font-size:11px;color:#C4860A;margin-top:2px;">Tampilkan "Hey, [Nama]"</p>
            </div>
            <label class="cursor-pointer"><input type="checkbox" id="tog-nama" name="show_guest_name" class="tog-input" {{ $landing->show_guest_name ? 'checked' : '' }} /><div class="tog-track"></div></label>
          </div>
          <div class="flex items-center justify-between py-[11px]">
            <div>
              <p style="font-size:13px;font-weight:500;color:#3D2B1A;">Mode Privat</p>
              <p style="font-size:11px;color:#C4860A;margin-top:2px;">Hanya bisa diakses via link</p>
            </div>
            <label class="cursor-pointer"><input type="checkbox" id="tog-privat" name="is_private" class="tog-input" {{ $landing->is_private ? 'checked' : '' }} /><div class="tog-track"></div></label>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-head">
          <span>Review Aktif</span>
          <div class="live-badge">
            <span class="live-dot"></span>Live
          </div>
        </div>
        <div class="review-content">
          <div style="border-radius:8px;overflow:hidden;width:100%;min-height:200px;">
            <div style="width:100%;min-height:200px;display:flex;align-items:center;justify-content:center;position:relative;background:linear-gradient(160deg,#3A2010 0%,#7A5230 40%,#C4A07A 100%);">
              <div style="text-align:center;color:#fff;">
                <p style="font-size:8px;letter-spacing:2.5px;text-transform:uppercase;color:rgba(255,255,255,.4);margin-bottom:8px;">The Wedding of</p>
                <p style="font-family:'Playfair Display',serif;font-size:18px;line-height:1.375;">{{ $landing->groom_name ?? 'Groom' }}<br><span style="font-size:13px;color:rgba(255,255,255,.5);">&</span><br>{{ $landing->bride_name ?? 'Bride' }}</p>
                <div style="width:20px;height:1px;background:rgba(255,255,255,.25);margin:8px auto;"></div>
                <p style="font-size:7px;letter-spacing:2px;color:rgba(255,255,255,.4);">14 · 09 · 2025</p>
              </div>
              <div style="position:absolute;bottom:0;left:0;right:0;padding:8px 12px;background:linear-gradient(to top,rgba(0,0,0,.6),transparent);">
                <p style="font-family:'Playfair Display',serif;font-style:italic;color:#fff;font-size:13px;" id="previewLabel">Bohemian Template</p>
              </div>
            </div>
          </div>

          <div style="display:grid;grid-template-columns:repeat(3,1fr);margin-top:12px;padding-top:12px;border-top:1px solid #E8D8C4;text-align:center;">
            <div>
              <p style="font-size:16px;font-weight:600;color:#3D2B1A;">100</p>
              <p style="font-size:9px;text-transform:uppercase;letter-spacing:.8px;color:#9A7B5C;margin-top:2px;">Tamu</p>
            </div>
            <div style="border-left:1px solid #E8D8C4;border-right:1px solid #E8D8C4;">
              <p style="font-size:16px;font-weight:600;color:#3D2B1A;">83</p>
              <p style="font-size:9px;text-transform:uppercase;letter-spacing:.8px;color:#9A7B5C;margin-top:2px;">Dibuka</p>
            </div>
            <div>
              <p style="font-size:16px;font-weight:600;color:#3D2B1A;">83%</p>
              <p style="font-size:9px;text-transform:uppercase;letter-spacing:.8px;color:#9A7B5C;margin-top:2px;">Open Rate</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    -->
    <input type="file" id="galleryFileInput" accept="image/*" multiple 
       class="hidden" onchange="handleGalleryUpload(event)" />
    </form>
  </div>
</main>

<script>
  // Load initial gallery from server
  const initialGallery = JSON.parse(`{!! $landing->gallery ?? '[]' !!}`);
</script>

</body>
</html>