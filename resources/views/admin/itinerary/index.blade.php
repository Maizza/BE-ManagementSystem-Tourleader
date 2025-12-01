@extends('layouts.app')

@section('content')
<style>
    .itinerary-card {
        border: none;
        border-radius: 14px;
        transition: 0.25s ease;
        background: #ffffff;
        box-shadow: 0 4px 14px rgba(0,0,0,0.06);
    }
    .itinerary-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 22px rgba(0,0,0,0.08);
    }

    .info-row span {
        display: block;
        font-size: 14px;
    }

    .label-text {
        font-weight: 600;
        color: #6c757d;
        font-size: 13px;
    }

    .value-text {
        color: #333;
        font-size: 15px;
        font-weight: 500;
    }

    .action-btn {
        width: 36px;
        height: 36px;
        border-radius: 8px !important;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    /* Tooltip Bootstrap improve */
    [data-bs-toggle="tooltip"] {
        cursor: pointer;
    }
</style>

<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Itinerary</h3>
            <span class="text-muted">Kelola itinerary dengan tampilan baru</span>
        </div>

        <a href="{{ route('admin.itinerary.form1') }}" class="btn btn-primary px-4 shadow-sm rounded-pill">
            <i class="bi bi-plus-lg me-1"></i> Tambah Itinerary
        </a>
    </div>

    @if(session('ok'))
        <div class="alert alert-success">{{ session('ok') }}</div>
    @endif

    @forelse($itineraries as $it)
    <div class="card itinerary-card mb-3 p-3">

        <div class="d-flex justify-content-between flex-wrap">

            <!-- Info Kiri -->
            <div style="max-width: 70%">
                <h5 class="fw-bold mb-2">{{ $it->title }}</h5>

                <div class="d-flex flex-wrap gap-3">

                    <div class="info-row">
                        <span class="label-text">Tanggal</span>
                        <span class="value-text">
                            @if($it->start_date) {{ $it->start_date->format('d M Y') }} @endif
                            @if($it->end_date) – {{ $it->end_date->format('d M Y') }} @endif
                        </span>
                    </div>

                    <div class="info-row">
                        <span class="label-text">Tour Leader</span>
                        <span class="value-text">
                            {{ $it->tourLeaders->pluck('name')->join(', ') ?: '-' }}
                        </span>
                    </div>

                    <div class="info-row">
                        <span class="label-text">Durasi</span>
                        <span class="value-text">{{ $it->days_count }} hari</span>
                    </div>

                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="d-flex align-items-start gap-2">

                <a href="{{ route('admin.itinerary.show', $it) }}"
                   class="btn btn-success btn-sm action-btn"
                   data-bs-toggle="tooltip" title="Lihat Detail">
                    <i class="bi bi-journal-text"></i>
                </a>

                <a href="{{ route('admin.itinerary.edit', $it) }}"
                   class="btn btn-outline-secondary btn-sm action-btn"
                   data-bs-toggle="tooltip" title="Edit Itinerary">
                    <i class="bi bi-pencil"></i>
                </a>

                <form action="{{ route('admin.itinerary.destroy', $it) }}"
                      method="POST"
                      onsubmit="return confirm('Hapus itinerary ini?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-outline-danger btn-sm action-btn"
                            data-bs-toggle="tooltip" title="Hapus">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>

            </div>
        </div>

    </div>
    @empty
        <div class="text-center py-5 text-muted">Belum ada itinerary.</div>
    @endforelse

  
</div>

<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>

@endsection
