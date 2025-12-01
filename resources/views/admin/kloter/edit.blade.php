@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 700px;">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="fas fa-edit"></i> Edit Kloter</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('kloter.update', $kloter->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Nama Kloter</label>
                    <input type="text" name="nama" class="form-control" value="{{ $kloter->nama }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tanggal</label>
                    <input type="text" name="tanggal" class="form-control" value="{{ $kloter->tanggal }}" required>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('kloter.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
