@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 700px;">
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            <h4 class="mb-0"><i class="fas fa-plus"></i> Tambah Kloter</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('kloter.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nama Kloter</label>
                    <input type="text" name="nama" class="form-control" placeholder="Masukkan nama kloter" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tanggal</label>
                    <input type="text" name="tanggal" class="form-control" placeholder="cth: 13 - 20 September 2025" required>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('kloter.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
