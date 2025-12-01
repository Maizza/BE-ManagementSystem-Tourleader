@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 900px">

  <!-- Header -->
  <div class="d-flex justify-content-between align-items-start mb-4 pb-2 border-bottom border-2">
    <div>
      <h4 class="fw-bold mb-1 text-dark">{{ $task->title }}</h4>
      <div class="small text-muted">
        <i class="bi bi-calendar-week me-1"></i> Dibuka: 
        <span class="fw-semibold text-success">{{ $task->opens_at->format('d M Y · H:i') }}</span>
        <span class="mx-2">—</span>
        <i class="bi bi-calendar-x me-1"></i> Ditutup: 
        <span class="fw-semibold text-danger">{{ $task->closes_at->format('d M Y · H:i') }}</span>
      </div>
    </div>
    <a href="{{ route('admin.ceklis.result', $task) }}" class="btn btn-outline-primary btn-sm rounded-pill shadow-sm">
      <i class="bi bi-clipboard-check me-1"></i> Lihat Hasil
    </a>
  </div>

  <!-- Daftar Pertanyaan -->
  <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
    <div class="card-header bg-primary text-white fw-semibold py-3">
      <i class="bi bi-list-check me-2"></i> Daftar Pertanyaan Ceklis
    </div>

    <div class="list-group list-group-flush">
      @forelse ($task->questions as $q)
        <div class="list-group-item d-flex align-items-start px-4 py-3 border-0 border-bottom">
          <div class="me-3">
            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
              {{ $q->order_no }}
            </div>
          </div>
          <div>
            <div class="fw-semibold text-dark">{{ $q->question_text }}</div>
          </div>
        </div>
      @empty
        <div class="list-group-item text-center py-4 text-muted">
          <i class="bi bi-info-circle me-1"></i> Belum ada pertanyaan untuk tugas ini.
        </div>
      @endforelse
    </div>
  </div>

  <!-- Footer -->
  <div class="mt-4 text-center">
    <a href="{{ route('admin.ceklis.index') }}" class="btn btn-light border rounded-pill px-4">
      <i class="bi bi-arrow-left me-1"></i> Kembali ke daftar tugas
    </a>
  </div>

</div>
@endsection
