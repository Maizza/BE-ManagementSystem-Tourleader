@extends('layouts.app')

@section('content')
<style>
    .page-title {
        font-weight: 700;
        font-size: 1.6rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .glass-card {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(10px);
        border-radius: 14px;
        padding: 25px;
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 4px 14px rgba(0,0,0,0.07);
    }

    .form-control {
        border-radius: 10px;
        padding: 10px 14px;
        border: 1px solid #dcdcdc;
        height: 45px;
        transition: 0.2s;
    }
    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 6px rgba(13,110,253,0.3);
    }

    .btn-modern {
        border-radius: 10px;
        padding: 10px 20px;
        font-weight: 600;
        transition: 0.25s ease;
    }
    .btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .btn-back {
        border-radius: 10px;
        padding: 10px 20px;
        font-weight: 600;
    }
</style>

<div class="container">

    <h4 class="page-title mb-3">
        <i class="bi bi-geo-alt-fill text-primary"></i> Tambah Kota Baru
    </h4>

    <div class="glass-card">

        <form action="{{ route('admin.itinerary.kota.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Kota</label>
                <input 
                    type="text" 
                    class="form-control" 
                    name="name" 
                    placeholder="Masukkan nama kota..." 
                    required
                >
            </div>

            <div class="d-flex gap-2 mt-4">
                <button class="btn btn-primary btn-modern">
                    <i class="bi bi-check-circle"></i> Simpan
                </button>

                <a href="{{ route('admin.itinerary.kota.index') }}" 
                   class="btn btn-light btn-back">
                   <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>

        </form>

    </div>
</div>
@endsection
