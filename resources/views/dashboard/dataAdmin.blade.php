<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>{{ $landing->groom_name ?? 'Groom' }} & {{ $landing->bride_name ?? 'Bride' }} — Data Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="/css/dashboard.css"/>
    <link rel="stylesheet" href="/css/dataAdmin.css">
    <link rel="icon" type="image/jpg" href="{{ asset('favicon.jpg') }}">
</head>
<body>

<button class="hamburger" id="hamburger" aria-label="Menu">
  <span></span><span></span><span></span>
</button>
<div class="overlay" id="overlay"></div>

@include('dashboard.partials.sidebar')

<main class="main">
    <h1 class="page-title">Data Admin</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h2 style="margin:0">Daftar Akun</h2>
            <button class="btn btn-primary" onclick="openModal('modalTambah')">+ Tambah Akun</button>
        </div>
        
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Username</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->username }}</td>
                        <td>
                            <button class="btn btn-outline" onclick="openEditModal('{{ $user->id }}', '{{ $user->username }}')">Edit</button>
                            <form action="{{ route('dashboard.admin.destroy', $user->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Hapus akun ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</main>

<div class="modal-backdrop" id="modalTambah">
    <div class="modal">
        <div class="modal-header">
            <h3>Tambah Akun</h3>
            <button class="modal-close" onclick="closeModal('modalTambah')">&times;</button>
        </div>
        <form action="{{ route('dashboard.admin.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <div class="password-wrapper">
                    <input type="password" name="password" id="passwordTambah" class="form-control" required>
                    <button type="button" class="toggle-password" onclick="togglePass('passwordTambah', this)">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn btn-outline" onclick="closeModal('modalTambah')">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<div class="modal-backdrop" id="modalEdit">
    <div class="modal">
        <div class="modal-header">
            <h3>Edit Akun</h3>
            <button class="modal-close" onclick="closeModal('modalEdit')">&times;</button>
        </div>
        <form id="formEdit" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" id="editUsername" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Password <small>(Kosongkan jika tidak diubah)</small></label>
                <div class="password-wrapper">
                    <input type="password" name="password" id="passwordEdit" class="form-control">
                    <button type="button" class="toggle-password" onclick="togglePass('passwordEdit', this)">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn btn-outline" onclick="closeModal('modalEdit')">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(id) { 
    document.getElementById(id).classList.add('open'); 
}

function openEditModal(id, username) {
    document.getElementById('editUsername').value = username;
    document.getElementById('formEdit').action = `/dashboard/data-admin/${id}`;
    openModal('modalEdit');
}

function closeModal(id) { 
    document.getElementById(id).classList.remove('open'); 
}

document.querySelectorAll('.modal-backdrop').forEach(el => {
    el.addEventListener('click', e => { if (e.target === el) el.classList.remove('open'); });
});

function togglePass(inputId, btn) {
    const input = document.getElementById(inputId);
    const svg = btn.querySelector('svg');
    if (input.type === 'password') {
        input.type = 'text';
        svg.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';
    } else {
        input.type = 'password';
        svg.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
    }
}

const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('overlay');
const hamburger = document.getElementById('hamburger');

hamburger?.addEventListener('click', () => { 
    sidebar.classList.toggle('open'); 
    overlay.classList.toggle('open'); 
});

overlay?.addEventListener('click', () => { 
    sidebar.classList.remove('open'); 
    overlay.classList.remove('open'); 
});
</script>
</body>
</html>