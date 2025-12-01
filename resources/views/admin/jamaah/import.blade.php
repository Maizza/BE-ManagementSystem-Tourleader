{{-- resources/views/admin/jamaah/import.blade.php --}}
@extends('layouts.app')

@section('title', 'Import Jamaah dari Excel')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Import Jamaah dari Excel</h1>

        <a href="{{ route('jamaah.index') }}" class="btn btn-secondary">
            &laquo; Kembali ke Data Jamaah
        </a>
    </div>

    {{-- Alert sukses / error --}}
        {{-- Alert sukses / error --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ✅ Tambahan: detail baris yang dilewati saat import --}}
    @if (session('import_errors'))
        <div class="alert alert-warning">
            <strong>Beberapa baris tidak di-import:</strong>
            <ul class="mb-0">
                @foreach (session('import_errors') as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <p class="mb-3">
                Silakan upload file Excel dengan format header:<br>
                <code>nama_jamaah, no_paspor, no_hp, jenis_kelamin, tanggal_lahir, kode_kloter, nomor_bus, keterangan</code>
            </p>

            <form action="{{ route('jamaah.import') }}" 
                  method="POST" 
                  enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="file" class="form-label">File Excel (.xlsx / .xls / .csv)</label>
                    <input type="file" name="file" id="file" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">
                    Upload &amp; Import
                </button>
            </form>
        </div>
    </div>

@endsection
