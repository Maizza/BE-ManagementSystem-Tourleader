@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width: 950px;">
  <!-- Header -->
  <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
    <h4 class="fw-bold mb-0 text-dark">
      <i class="bi bi-clipboard-check text-primary me-2"></i> Daftar Tugas Ceklis
    </h4>
    <a href="{{ route('admin.ceklis.create.step1') }}" class="btn btn-primary rounded-pill shadow-sm px-3">
      <i class="bi bi-plus-lg me-1"></i> Tambah Tugas Ceklis
    </a>
  </div>

  <!-- Notifikasi -->
  @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <!-- List Tugas -->
  @forelse ($tasks as $t)
    <div class="card shadow-sm border-0 mb-3 rounded-4 hover-shadow">
      <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div>
          <h5 class="fw-semibold mb-1 text-dark">{{ $t->title }}</h5>
          <div class="small text-muted">
            <div><i class="bi bi-calendar-event me-1"></i> Dibuka: <strong>{{ $t->opens_at->format('d M Y · H:i') }}</strong></div>
            <div><i class="bi bi-calendar-x me-1"></i> Ditutup: <strong>{{ $t->closes_at->format('d M Y · H:i') }}</strong></div>
            <div><i class="bi bi-list-ol me-1"></i> Jumlah soal: <strong>{{ $t->question_count }}</strong></div>
          </div>
        </div>

        @php
          $map = [
            'belum_dibuka' => ['bg-warning text-dark', 'bi-clock', 'Belum dibuka'],
            'dibuka'       => ['bg-success text-white', 'bi-unlock', 'Sedang dibuka'],
            'ditutup'      => ['bg-danger text-white', 'bi-lock-fill', 'Sudah ditutup'],
          ];
          [$badgeClass, $icon, $label] = $map[$t->status];
        @endphp

        <div class="d-flex align-items-center gap-2">
          <span class="badge {{ $badgeClass }} px-3 py-2">
            <i class="bi {{ $icon }} me-1"></i> {{ $label }}
          </span>
          <a href="{{ route('admin.ceklis.show', $t) }}" class="btn btn-outline-secondary btn-sm rounded-pill">
            <i class="bi bi-eye me-1"></i> Detail Soal
          </a>
          <a href="{{ route('admin.ceklis.result', $t) }}" class="btn btn-dark btn-sm rounded-pill">
            <i class="bi bi-bar-chart-line me-1"></i> Detail Hasil
          </a>
        </div>
      </div>
    </div>
  @empty
    <div class="alert alert-secondary text-center py-4 rounded-3 shadow-sm">
      <i class="bi bi-info-circle me-2"></i> Belum ada tugas ceklis. Klik <strong>“Tambah Tugas Ceklis”</strong> untuk membuat baru.
    </div>
  @endforelse

  <!-- Pagination -->
  <div class="mt-4 d-flex justify-content-center">
    {{ $tasks->links() }}
  </div>
</div>

{{-- Sedikit efek hover halus untuk card --}}
<style>
  .hover-shadow:hover {
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    transform: translateY(-2px);
    transition: all 0.2s ease-in-out;
  }
</style>
@endsection
