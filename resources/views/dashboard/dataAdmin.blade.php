<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Nicholas & Nahda — Data Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="/css/dashboard.css"/>
    <link rel="stylesheet" href="/css/dataAdmin.css">
</head>
<body>

<button class="hamburger" id="hamburger" aria-label="Menu">
  <span></span><span></span><span></span>
</button>
<div class="overlay" id="overlay"></div>

@include('dashboard.partials.sidebar')

<main class="main">
    <h1 class="page-title">Data Admin & User</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
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
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->username }}</td>
                        <td>{{ ucfirst($user->role) }}</td>
                        <td>
                            <button class="btn btn-outline" onclick="openEditModal('{{ $user->id }}', '{{ $user->username }}', '{{ $user->role }}')">Edit</button>
                            <form action="{{ route('dashboard.admin.destroy', $user->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn" style="background:#e05a52; color:white;" onclick="return confirm('Hapus akun ini?')">Hapus</button>
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
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Role</label>
                <select name="role" class="form-control" required>
                    <option value="tamu">Tamu</option>
                    <option value="admin">Admin</option>
                </select>
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
                <input type="password" name="password" class="form-control">
            </div>
            <div class="form-group">
                <label>Role</label>
                <select name="role" id="editRole" class="form-control" required>
                    <option value="tamu">Tamu</option>
                    <option value="admin">Admin</option>
                </select>
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

function openEditModal(id, username, role) {
    document.getElementById('editUsername').value = username;
    document.getElementById('editRole').value = role;
    document.getElementById('formEdit').action = `/dashboard/data-admin/${id}`;
    openModal('modalEdit');
}

function closeModal(id) { 
    document.getElementById(id).classList.remove('open'); 
}

document.querySelectorAll('.modal-backdrop').forEach(el => {
    el.addEventListener('click', e => { if (e.target === el) el.classList.remove('open'); });
});

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