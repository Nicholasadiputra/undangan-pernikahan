@extends('layouts.dashboard')

@section('content')
<link rel="stylesheet" href="{{ asset('css/editLanding.css') }}">

<div class="container">
    <h2>Edit Landing Page</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('landing.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label>Judul</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $landing->title) }}">
        </div>

        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="description" class="form-control">{{ old('description', $landing->description) }}</textarea>
        </div>

        <div class="form-group">
            <label>Gambar Hero</label>
            <input type="file" name="hero_image" class="form-control">
            @if($landing->hero_image)
                <img src="{{ asset('storage/' . $landing->hero_image) }}" alt="Hero Image" width="150" class="mt-2">
            @endif
        </div>

        <button type="submit" class="btn btn-primary mt-3">Simpan Perubahan</button>
    </form>
</div>
@endsection