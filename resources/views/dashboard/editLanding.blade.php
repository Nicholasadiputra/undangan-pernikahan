<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Edit Landing Page — {{ $landing->groom_name ?? "Groom" }} &amp; {{ $landing->bride_name ?? "Bride" }}</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />
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
            <p style="font-size:10px;color:#9A7B5C;margin-top:8px;">Isi minimal 1 dan maksimal 8 kegiatan. Baris berikutnya otomatis muncul.</p>
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
              <input type="color" id="globalColorPicker" style="opacity: 0; width: 10px; height: 10px; position: absolute; bottom: 0; z-index: -1;" oninput="commitColor(this.value)" onchange="commitColor(this.value)" />
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

        <div style="margin-top:20px;">
          <label class="lbl-dark">Custom Warna</label>
          <p style="font-size:10px;color:#9A7B5C;margin-bottom:12px;margin-top:2px;">Kustomisasi warna tema undangan Anda. Klik reset untuk kembali ke warna default template.</p>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">

            {{-- Primary --}}
            <div style="display:flex;flex-direction:column;gap:4px;">
              <label style="font-size:11px;font-weight:600;color:#5C4A3A;letter-spacing:.05em;text-transform:uppercase;">Primary</label>
              <p style="font-size:9px;color:#9A7B5C;margin:0;">Judul, nav, footer, border</p>
              <div style="display:flex;align-items:center;gap:8px;margin-top:4px;">
                <div id="preview_color_primary" style="width:28px;height:28px;border-radius:6px;border:1px solid #ddd;background:{{ $landing->color_primary ?? '#321E04' }};flex-shrink:0;"></div>
                <input type="text" id="txt_color_primary" name="color_primary" value="{{ $landing->color_primary ?? '#321E04' }}"
                  maxlength="7" oninput="syncColorInput('primary',this.value)"
                  style="flex:1;padding:5px 8px;border:1px solid #E8D8C4;border-radius:6px;font-size:12px;font-family:monospace;background:#FDF9F5;">
                <input type="color" id="pick_color_primary" value="{{ $landing->color_primary ?? '#321E04' }}"
                  oninput="syncColorPicker('primary',this.value)"
                  style="width:28px;height:28px;border:none;background:none;padding:0;cursor:pointer;border-radius:4px;">
              </div>
            </div>

            {{-- Accent --}}
            <div style="display:flex;flex-direction:column;gap:4px;">
              <label style="font-size:11px;font-weight:600;color:#5C4A3A;letter-spacing:.05em;text-transform:uppercase;">Accent</label>
              <p style="font-size:9px;color:#9A7B5C;margin:0;">Garis dekorasi, separator, tanggal</p>
              <div style="display:flex;align-items:center;gap:8px;margin-top:4px;">
                <div id="preview_color_accent" style="width:28px;height:28px;border-radius:6px;border:1px solid #ddd;background:{{ $landing->color_accent ?? '#C9A96E' }};flex-shrink:0;"></div>
                <input type="text" id="txt_color_accent" name="color_accent" value="{{ $landing->color_accent ?? '#C9A96E' }}"
                  maxlength="7" oninput="syncColorInput('accent',this.value)"
                  style="flex:1;padding:5px 8px;border:1px solid #E8D8C4;border-radius:6px;font-size:12px;font-family:monospace;background:#FDF9F5;">
                <input type="color" id="pick_color_accent" value="{{ $landing->color_accent ?? '#C9A96E' }}"
                  oninput="syncColorPicker('accent',this.value)"
                  style="width:28px;height:28px;border:none;background:none;padding:0;cursor:pointer;border-radius:4px;">
              </div>
            </div>

            {{-- Mid --}}
            <div style="display:flex;flex-direction:column;gap:4px;">
              <label style="font-size:11px;font-weight:600;color:#5C4A3A;letter-spacing:.05em;text-transform:uppercase;">Mid</label>
              <p style="font-size:9px;color:#9A7B5C;margin:0;">Teks sekunder, elemen halus</p>
              <div style="display:flex;align-items:center;gap:8px;margin-top:4px;">
                <div id="preview_color_mid" style="width:28px;height:28px;border-radius:6px;border:1px solid #ddd;background:{{ $landing->color_mid ?? '#7A5C3A' }};flex-shrink:0;"></div>
                <input type="text" id="txt_color_mid" name="color_mid" value="{{ $landing->color_mid ?? '#7A5C3A' }}"
                  maxlength="7" oninput="syncColorInput('mid',this.value)"
                  style="flex:1;padding:5px 8px;border:1px solid #E8D8C4;border-radius:6px;font-size:12px;font-family:monospace;background:#FDF9F5;">
                <input type="color" id="pick_color_mid" value="{{ $landing->color_mid ?? '#7A5C3A' }}"
                  oninput="syncColorPicker('mid',this.value)"
                  style="width:28px;height:28px;border:none;background:none;padding:0;cursor:pointer;border-radius:4px;">
              </div>
            </div>

            {{-- Background --}}
            <div style="display:flex;flex-direction:column;gap:4px;">
              <label style="font-size:11px;font-weight:600;color:#5C4A3A;letter-spacing:.05em;text-transform:uppercase;">Background</label>
              <p style="font-size:9px;color:#9A7B5C;margin:0;">Warna latar section terang</p>
              <div style="display:flex;align-items:center;gap:8px;margin-top:4px;">
                <div id="preview_color_bg" style="width:28px;height:28px;border-radius:6px;border:1px solid #ddd;background:{{ $landing->color_bg ?? '#f5f1eb' }};flex-shrink:0;"></div>
                <input type="text" id="txt_color_bg" name="color_bg" value="{{ $landing->color_bg ?? '#f5f1eb' }}"
                  maxlength="7" oninput="syncColorInput('bg',this.value)"
                  style="flex:1;padding:5px 8px;border:1px solid #E8D8C4;border-radius:6px;font-size:12px;font-family:monospace;background:#FDF9F5;">
                <input type="color" id="pick_color_bg" value="{{ $landing->color_bg ?? '#f5f1eb' }}"
                  oninput="syncColorPicker('bg',this.value)"
                  style="width:28px;height:28px;border:none;background:none;padding:0;cursor:pointer;border-radius:4px;">
              </div>
            </div>

          </div>

          {{-- Reset button --}}
          <div style="margin-top:12px;text-align:right;">
            <button type="button" onclick="resetColors()"
              style="font-size:11px;color:#9A7B5C;background:none;border:1px solid #E8D8C4;border-radius:6px;padding:5px 14px;cursor:pointer;">
              ↺ Reset ke Default Template
            </button>
          </div>
        </div>

      </div>
    </div>


    {{-- ═══ CARD 3: Kirim Undangan ═══ --}}
    <div class="card">
      <div class="card-head">Kirim Undangan Khusus</div>
      <div class="card-content">
        <p style="font-size:12px;color:#9A7B5C;margin-bottom:12px;">Buat link undangan khusus dengan nama tamu yang akan diundang.</p>
        
        <div style="display:flex; gap:12px; align-items:flex-end;">
          <div style="flex:1;">
            <label class="lbl-dark">Nama Tamu</label>
            <input type="text" id="guestNameInput" class="inp" placeholder="Masukkan nama tamu, contoh: Budi Santoso" />
          </div>
          <button type="button" class="btn btn-primary" onclick="generateLink()" style="padding: 10px 16px; height: 42px;">Buat Link</button>
        </div>

        <div id="generatedLinkContainer" style="display:none; margin-top:16px; padding:12px; background:#F8F5F0; border-radius:8px; border:1px solid #E8D8C4;">
          <label class="lbl-dark">Link Undangan</label>
          <div style="display:flex; gap:8px; margin-top:8px;">
            <input type="text" id="generatedLinkUrl" class="inp" readonly style="background:#fff;" />
            <button type="button" class="btn" onclick="copyLink()" style="background:#fff; border:1px solid #C4A47C; color:#3D2B1A; white-space:nowrap;">Copy</button>
            <button type="button" class="btn" onclick="sendWhatsApp()" style="background:#25D366; color:#fff; border:none; white-space:nowrap; display:flex; gap:6px; align-items:center;">
              <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/>
              </svg>
              Kirim WA
            </button>
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
  // Load initial data from server
  const initialGallery = JSON.parse(`{!! addcslashes($landing->gallery ?? '[]', '\`\\') !!}`);
  const initialKegiatan = JSON.parse(`{!! addcslashes($landing->kegiatan ?? 'null', '\`\\') !!}`);
  const initialPalette = JSON.parse(`{!! addcslashes($landing->palette_colors ?? 'null', '\`\\') !!}`);

  function generateLink() {
    const name = document.getElementById('guestNameInput').value.trim();
    if (!name) {
      alert('Silakan masukkan nama tamu terlebih dahulu.');
      return;
    }
    const baseUrl = "{{ url('/') }}";
    const generatedUrl = baseUrl + "?to=" + encodeURIComponent(name);
    
    document.getElementById('generatedLinkUrl').value = generatedUrl;
    document.getElementById('generatedLinkContainer').style.display = 'block';
  }

  function copyLink() {
    const urlInput = document.getElementById('generatedLinkUrl');
    urlInput.select();
    document.execCommand('copy');
    alert('Link berhasil disalin!');
  }

  function sendWhatsApp() {
    const name = document.getElementById('guestNameInput').value.trim();
    const url = document.getElementById('generatedLinkUrl').value;
    const message = `Halo ${name},\n\nKami mengundang Anda untuk hadir di acara pernikahan kami.\n\nSilakan buka link berikut untuk melihat undangan selengkapnya:\n${url}\n\nTerima kasih,\n{{ $landing->groom_name ?? 'Groom' }} & {{ $landing->bride_name ?? 'Bride' }}`;
    const waUrl = "https://wa.me/?text=" + encodeURIComponent(message);
    window.open(waUrl, '_blank');
  }
</script>

</body>
</html>