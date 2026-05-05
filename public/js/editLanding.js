// ═══ SIDEBAR ═══
const sidebar   = document.getElementById('sidebar');
const overlay   = document.getElementById('overlay');
const hamburger = document.getElementById('hamburger');
hamburger.addEventListener('click', () => { sidebar.classList.toggle('open'); overlay.classList.toggle('open'); });
overlay.addEventListener('click', () => { sidebar.classList.remove('open'); overlay.classList.remove('open'); });

// ═══ GALLERY ═══
const SLOTS = {
  landing:        { label: 'Landing',               max: 1 },
  ucapan1:        { label: 'Ucapan 1',              max: 1 },
  ucapan2:        { label: 'Ucapan 2',              max: 1 },
  ucapan3:        { label: 'Ucapan 3',              max: 1 },
  save_the_date:  { label: 'Save the Date',         max: 1 },
  venue:          { label: 'Venue',                  max: 1 },
  dresscode1:     { label: 'Dress Code 1',          max: 1 },
  dresscode2:     { label: 'Dress Code 2',          max: 1 },
  dresscode3:     { label: 'Dress Code 3',          max: 1 },
  dresscode4:     { label: 'Dress Code 4',          max: 1 },
  story1:         { label: 'Story 1',               max: 1 },
  story2:         { label: 'Story 2',               max: 1 },
  ayah_pria:      { label: 'Ayah Mempelai Pria',   max: 1 },
  ibu_pria:       { label: 'Ibu Mempelai Pria',    max: 1 },
  ayah_wanita:    { label: 'Ayah Mempelai Wanita', max: 1 },
  ibu_wanita:     { label: 'Ibu Mempelai Wanita',  max: 1 },
  galeri:         { label: 'Galeri',                max: Infinity },
  penutup:        { label: 'Penutup',               max: 1 },
};

const imageSlots = {};
const slotCounts = {};
Object.keys(SLOTS).forEach(k => slotCounts[k] = 0);

let imgCounter   = 0;
let activeMenuId = null;

// galleryImages: { id, url (base64 preview), savedPath (dari server setelah upload) }
const galleryImages = [];

// Load initial gallery from server
if (typeof initialGallery !== 'undefined') {
  initialGallery.forEach(item => {
    const id = 'img' + (++imgCounter);
    galleryImages.push({
      id: id,
      url: item.url,
      savedPath: item.path
    });
    if (item.slot) {
      imageSlots[id] = item.slot;
      slotCounts[item.slot] = (slotCounts[item.slot] || 0) + 1;
    }
  });
  renderGallery(); // Render after loading initial
}

function slotAvailable(key, forImage) {
  const current = imageSlots[forImage];
  const taken   = slotCounts[key] || 0;
  const max     = SLOTS[key].max;
  if (current === key) return true;
  return taken < max;
}

function assignSlot(imgId, slotKey) {
  const prev = imageSlots[imgId];
  if (prev) slotCounts[prev] = Math.max(0, (slotCounts[prev] || 0) - 1);
  imageSlots[imgId] = slotKey;
  slotCounts[slotKey] = (slotCounts[slotKey] || 0) + 1;
  updateBadge(imgId, slotKey);
  closeMenu(imgId);
  syncGalleryToForm();
}

function updateBadge(imgId, slotKey) {
  const badge = document.getElementById('badge-' + imgId);
  if (badge) {
    badge.textContent   = SLOTS[slotKey].label;
    badge.style.display = 'block';
  }
}

function renderGallery() {
  const grid = document.getElementById('gallery-grid');
  grid.innerHTML = '';

  galleryImages.forEach(img => {
    const item = document.createElement('div');
    item.className = 'gallery-item';
    item.id = 'gitem-' + img.id;

    const uploading = !img.savedPath
      ? `<div style="position:absolute;inset:0;background:rgba(0,0,0,.4);display:flex;align-items:center;justify-content:center;border-radius:10px;z-index:5;">
           <span style="color:#fff;font-size:10px;">Uploading...</span>
         </div>`
      : '';

    const thumbInner = img.url
      ? `<img src="${img.url}" alt="foto" style="width:100%;height:100%;object-fit:cover;" />`
      : `<div style="width:100%;height:100%;background:linear-gradient(135deg,#6B4C2A,#C4A47C,#4A2C10);display:flex;align-items:center;justify-content:center;flex-direction:column;gap:4px;">
           <svg width="24" height="24" fill="none" stroke="rgba(255,255,255,.4)" stroke-width="1.5" viewBox="0 0 24 24">
             <rect x="3" y="3" width="18" height="18" rx="2"/>
             <circle cx="8.5" cy="8.5" r="1.5"/>
             <polyline points="21 15 16 10 5 21"/>
           </svg>
           <span style="font-size:9px;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.3)">Foto</span>
         </div>`;

    item.innerHTML = `
      ${uploading}
      <div class="thumb" style="width:100%;height:100%;border-radius:10px;overflow:hidden;border:1.5px solid #E8D8C4;">${thumbInner}</div>
      <div id="badge-${img.id}"
        style="display:none;position:absolute;bottom:0;left:0;right:0;text-align:center;
               font-size:9px;font-weight:600;letter-spacing:.05em;text-transform:uppercase;
               color:#fff;background:rgba(0,0,0,.55);padding:4px 0;border-radius:0 0 9px 9px;pointer-events:none;">
      </div>
      <button type="button" class="ham-btn" onclick="toggleMenu('${img.id}', event)">
        <span></span><span></span><span></span>
      </button>
      <div id="hmenu-${img.id}" class="ham-menu">
        <div class="ham-menu-header">Letakkan di bagian</div>
        <div style="max-height:220px;overflow-y:auto;">${buildMenuItems(img.id)}</div>
        <div style="border-top:1px solid #E8D8C4;">
          <div class="ham-menu-item" style="color:#C0392B;" onclick="removeImage('${img.id}')">
            <span>Hapus foto ini</span>
          </div>
        </div>
      </div>
    `;
    grid.appendChild(item);
    if (imageSlots[img.id]) updateBadge(img.id, imageSlots[img.id]);
  });

  // Tombol tambah foto
  const uploadEl = document.createElement('div');
  uploadEl.className = 'gallery-upload cursor-pointer';
  uploadEl.style.cssText = 'aspect-ratio:1/1;border-radius:10px;border:1.5px dashed #E8D8C4;background:#F5EDE0;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:5px;cursor:pointer;';
  uploadEl.onclick = () => {
    const fi = document.createElement('input');
    fi.type     = 'file';
    fi.accept   = 'image/*';
    fi.multiple = true;
    fi.style.display = 'none';
    document.body.appendChild(fi);
    fi.onchange = (e) => {
      handleGalleryUpload(e);
      document.body.removeChild(fi);
    };
    fi.click();
  };
  uploadEl.innerHTML = `
    <svg width="24" height="24" fill="none" stroke="#9A7B5C" stroke-width="1.8" viewBox="0 0 24 24">
      <path d="M12 5v14M5 12h14"/>
    </svg>
    <span style="font-size:11px;font-weight:500;color:#9A7B5C;">Tambah Foto</span>
  `;
  grid.appendChild(uploadEl);
}

function buildMenuItems(imgId) {
  return Object.entries(SLOTS).map(([key, slot]) => {
    const selected  = imageSlots[imgId] === key;
    const available = slotAvailable(key, imgId);
    const taken     = slotCounts[key] || 0;
    let cls = 'ham-menu-item';
    if (selected) cls += ' selected';
    else if (!available) cls += ' taken';
    const badge   = slot.max === Infinity
      ? `<span class="slot-badge">${taken} foto</span>`
      : `<span class="slot-badge">${taken}/${slot.max}</span>`;
    const onclick = (selected || !available) ? '' : `onclick="assignSlot('${imgId}','${key}')"`;
    return `<div class="${cls}" ${onclick}><span>${slot.label}</span>${badge}</div>`;
  }).join('');
}

function toggleMenu(imgId, e) {
  e.stopPropagation();
  if (activeMenuId && activeMenuId !== imgId) closeMenu(activeMenuId);
  const menu = document.getElementById('hmenu-' + imgId);
  if (!menu) return;
  // refresh items
  const inner = menu.querySelector('[style*="max-height"]');
  if (inner) inner.innerHTML = buildMenuItems(imgId);
  if (menu.classList.contains('open')) {
    menu.classList.remove('open');
    activeMenuId = null;
  } else {
    menu.classList.add('open');
    activeMenuId = imgId;
  }
}

function closeMenu(imgId) {
  const menu = document.getElementById('hmenu-' + imgId);
  if (menu) menu.classList.remove('open');
  if (activeMenuId === imgId) activeMenuId = null;
}

document.addEventListener('click', () => { if (activeMenuId) closeMenu(activeMenuId); });

function removeImage(imgId) {
  const prev = imageSlots[imgId];
  if (prev) slotCounts[prev] = Math.max(0, (slotCounts[prev] || 0) - 1);
  delete imageSlots[imgId];
  const idx = galleryImages.findIndex(i => i.id === imgId);
  if (idx !== -1) galleryImages.splice(idx, 1);
  renderGallery();
  syncGalleryToForm();
}

function handleGalleryUpload(e) {
  const files = Array.from(e.target.files);
  if (!files.length) return;

  files.forEach(file => {
    const id = 'img' + (++imgCounter);

    // Preview dulu
    const reader = new FileReader();
    reader.onload = ev => {
      galleryImages.push({ id, url: ev.target.result, savedPath: null });
      renderGallery();

      // Upload ke server langsung
      const fd = new FormData();
      fd.append('file', file);
      fd.append('_token', document.querySelector('meta[name="csrf-token"]').content);

      fetch('/dashboard/landing/upload-gallery', {
        method: 'POST',
        body: fd,
      })
      .then(r => r.json())
      .then(data => {
        const img = galleryImages.find(i => i.id === id);
        if (img) {
          img.savedPath = data.path;
          renderGallery(); // hilangkan "uploading..." overlay
          syncGalleryToForm();
        }
      })
      .catch(err => {
        alert('Gagal upload foto: ' + err.message);
        removeImage(id);
      });
    };
    reader.readAsDataURL(file);
  });
}

function syncGalleryToForm() {
  document.querySelectorAll('input[name="gallery_paths[]"]').forEach(el => el.remove());
  document.querySelectorAll('input[name="gallery_slots[]"]').forEach(el => el.remove());

  const form = document.getElementById('landingForm');
  galleryImages.forEach(img => {
    if (!img.savedPath) return;

    const pathInput = document.createElement('input');
    pathInput.type  = 'hidden';
    pathInput.name  = 'gallery_paths[]';
    pathInput.value = img.savedPath;
    form.appendChild(pathInput);

    const slotInput = document.createElement('input');
    slotInput.type  = 'hidden';
    slotInput.name  = 'gallery_slots[]';
    slotInput.value = imageSlots[img.id] || '';
    form.appendChild(slotInput);
  });
}

renderGallery();

// ═══ KEGIATAN ═══
const kegiatanList = [{ name: '', time: '', period: 'AM' }];

function renderKegiatan() {
  const container = document.getElementById('kegiatan-list');
  container.innerHTML = '';

  kegiatanList.forEach((item, idx) => {
    const row = document.createElement('div');
    row.className = 'kegiatan-row';
    row.dataset.idx = idx;
    row.innerHTML = `
      <div class="inp-icon-wrap" style="flex:1;">
        <svg class="inp-icon" style="width:14px;height:14px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
        </svg>
        <input type="text" name="kegiatan_name[]" class="inp"
          style="padding-left:32px;padding-right:8px;font-size:13px;"
          placeholder="Kegiatan ${idx + 1}" value="${item.name}"
          oninput="onKegiatanInput(${idx}, 'name', this.value)" />
      </div>
      <div style="display:flex;align-items:center;gap:4px;flex-shrink:0;">
        <input type="time" name="kegiatan_time[]" class="time-inp" value="${item.time}"
          onchange="onKegiatanInput(${idx}, 'time', this.value)" title="Waktu" />
        <div class="ampm-toggle">
          <button type="button" class="ampm-btn ${item.period === 'AM' ? 'active' : ''}" onclick="setPeriod(${idx}, 'AM')">AM</button>
          <button type="button" class="ampm-btn ${item.period === 'PM' ? 'active' : ''}" onclick="setPeriod(${idx}, 'PM')">PM</button>
        </div>
        <input type="hidden" name="kegiatan_period[]" value="${item.period}" id="period-inp-${idx}" />
      </div>
      ${kegiatanList.length > 1
        ? `<button type="button" class="remove-btn" onclick="removeKegiatan(${idx})">
             <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
               <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
             </svg>
           </button>`
        : '<div style="width:28px;"></div>'
      }
    `;
    container.appendChild(row);
  });
}

function setPeriod(idx, val) {
  kegiatanList[idx].period = val;
  const hidden = document.getElementById('period-inp-' + idx);
  if (hidden) hidden.value = val;
  renderKegiatan();
}

function onKegiatanInput(idx, field, val) {
  kegiatanList[idx][field] = val;
  if (field === 'name' && val.trim() !== '' && idx === kegiatanList.length - 1) {
    kegiatanList.push({ name: '', time: '', period: 'AM' });
    renderKegiatan();
    setTimeout(() => {
      const rows = document.querySelectorAll('#kegiatan-list .kegiatan-row');
      const newRow = rows[rows.length - 1];
      if (newRow) { const inp = newRow.querySelector('input[type="text"]'); if (inp) inp.focus(); }
    }, 50);
  } else if (field === 'name' && val.trim() === '' && kegiatanList.length > 1) {
    trimTrailingEmpty();
  }
}

function trimTrailingEmpty() {
  while (
    kegiatanList.length > 1 &&
    kegiatanList[kegiatanList.length - 1].name.trim() === '' &&
    kegiatanList[kegiatanList.length - 2].name.trim() === ''
  ) { kegiatanList.pop(); }
  renderKegiatan();
}

function removeKegiatan(idx) {
  kegiatanList.splice(idx, 1);
  if (kegiatanList.length === 0) kegiatanList.push({ name: '', time: '', period: 'AM' });
  if (kegiatanList[kegiatanList.length - 1].name.trim() !== '') {
    kegiatanList.push({ name: '', time: '', period: 'AM' });
  }
  renderKegiatan();
}

renderKegiatan();

// ═══ PALETTE ═══
const paletteColors  = ['#FAF6F0', '#E8C98A', '#A07850', '#5C3A1E', '#1A1A1A'];
let activeSwatchIdx  = 0;
let editingSwatchIdx = null;

function renderPalette() {
  const row = document.getElementById('palette-row');
  row.innerHTML = '';

  paletteColors.forEach((color, i) => {
    const wrap = document.createElement('div');
    wrap.className = 'swatch-wrap';

    const btn = document.createElement('button');
    btn.type      = 'button';
    btn.className = 'palette-swatch' + (i === activeSwatchIdx ? ' active' : '');
    btn.style.background = color;
    btn.title     = color;
    btn.onclick   = () => {
      if (activeSwatchIdx === i) {
        editingSwatchIdx = i;
        const picker = document.getElementById('globalColorPicker');
        picker.value = color; picker.click();
      } else { activeSwatchIdx = i; renderPalette(); }
    };
    wrap.appendChild(btn);

    const editBtn = document.createElement('button');
    editBtn.type      = 'button';
    editBtn.className = 'swatch-edit';
    editBtn.innerHTML = '✏';
    editBtn.onclick   = e => {
      e.stopPropagation();
      editingSwatchIdx = i;
      const picker = document.getElementById('globalColorPicker');
      picker.value = color; picker.click();
    };
    wrap.appendChild(editBtn);

    const delBtn = document.createElement('button');
    delBtn.type      = 'button';
    delBtn.className = 'swatch-delete';
    delBtn.innerHTML = '×';
    delBtn.onclick   = e => {
      e.stopPropagation();
      paletteColors.splice(i, 1);
      if (activeSwatchIdx >= paletteColors.length) activeSwatchIdx = paletteColors.length - 1;
      if (activeSwatchIdx < 0) activeSwatchIdx = 0;
      renderPalette();
    };
    wrap.appendChild(delBtn);
    row.appendChild(wrap);
  });

  const addBtn = document.createElement('button');
  addBtn.type      = 'button';
  addBtn.className = 'add-color-btn';
  addBtn.innerHTML = '+';
  addBtn.onclick   = () => {
    editingSwatchIdx = null;
    const picker = document.getElementById('globalColorPicker');
    picker.value = '#C4860A'; picker.click();
  };
  row.appendChild(addBtn);

  const hint = document.createElement('p');
  hint.style.cssText = 'width:100%;font-size:10px;color:#9A7B5C;margin-top:6px;';
  hint.textContent   = 'Klik = pilih · Klik lagi atau ✏ = ubah · × = hapus';
  row.appendChild(hint);

  syncPaletteToForm();
}

function commitColor(val) {
  if (editingSwatchIdx !== null) { paletteColors[editingSwatchIdx] = val; }
  else { paletteColors.push(val); activeSwatchIdx = paletteColors.length - 1; }
  editingSwatchIdx = null;
  renderPalette();
}

function syncPaletteToForm() {
  document.querySelectorAll('input[name="palette_colors[]"]').forEach(el => el.remove());
  const form = document.getElementById('landingForm');
  paletteColors.forEach(c => {
    const inp = document.createElement('input');
    inp.type  = 'hidden';
    inp.name  = 'palette_colors[]';
    inp.value = c;
    form.appendChild(inp);
  });
}

renderPalette();

// ═══ TEMPLATE ═══
function selectTemplate(name) {
  document.getElementById('selected_template').value = name;
  const label = document.getElementById('previewLabel');
  if (label) label.textContent = name.charAt(0).toUpperCase() + name.slice(1) + ' Template';
  document.querySelectorAll('.tpl-card').forEach(c => c.classList.remove('selected'));
  document.getElementById('tpl-' + name).classList.add('selected');
}

function handleUploadLabel(input, labelId) {
  if (input.files && input.files[0]) {
    document.getElementById(labelId).textContent = input.files[0].name;
  }
}

// ═══ SUBMIT — biarkan form submit biasa, galeri sudah diupload via AJAX ═══
document.getElementById('landingForm').addEventListener('submit', function() {
  syncGalleryToForm();
  syncPaletteToForm();
  
});