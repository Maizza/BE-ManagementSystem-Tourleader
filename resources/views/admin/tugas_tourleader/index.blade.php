@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width: 950px">
  <!-- Header -->
  <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-2">
    <h4 class="fw-bold mb-0 text-dark">
      <i class="bi bi-list-task me-2 text-primary"></i> Daftar Tugas Tour Leader
    </h4>
    <a href="{{ route('admin.tasks.create.step1') }}" class="btn btn-primary rounded-pill shadow-sm px-3">
      <i class="bi bi-plus-lg me-1"></i> Buat Tugas Tour Leader
    </a>
  </div>

  <!-- Alert -->
  @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <!-- Daftar Tugas -->
  @forelse ($tasks as $task)
    <div class="card shadow-sm border-0 mb-3 rounded-4">
      <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div>
          <h5 class="fw-semibold mb-1 text-dark">{{ $task->title }}</h5>
          <div class="text-muted small">
            <div><i class="bi bi-calendar-event me-1"></i> Dibuka: <span class="fw-semibold">{{ $task->opens_at->format('d M Y · H:i') }}</span></div>
            <div><i class="bi bi-calendar-x me-1"></i> Ditutup: <span class="fw-semibold">{{ $task->closes_at->format('d M Y · H:i') }}</span></div>
            <div><i class="bi bi-question-circle me-1"></i> Jumlah soal: <strong>{{ $task->question_count }}</strong></div>
          </div>
        </div>

        @php
          $map = [
            'belum_dibuka' => ['class' => 'bg-warning text-dark', 'icon' => 'bi-clock', 'label' => 'Belum dibuka'],
            'dibuka'       => ['class' => 'bg-success text-white', 'icon' => 'bi-unlock', 'label' => 'Sedang dibuka'],
            'ditutup'      => ['class' => 'bg-danger text-white', 'icon' => 'bi-lock-fill', 'label' => 'Sudah ditutup'],
          ];
          $status = $task->status;
        @endphp

        <div class="d-flex align-items-center gap-2">
          <span class="badge {{ $map[$status]['class'] }} px-3 py-2">
            <i class="bi {{ $map[$status]['icon'] }} me-1"></i> {{ $map[$status]['label'] }}
          </span>
          <a href="{{ route('admin.tasks.show', $task) }}" class="btn btn-outline-secondary btn-sm rounded-pill">
            <i class="bi bi-eye me-1"></i> Detail
          </a>
          <a href="{{ route('admin.tasks.result', $task) }}" class="btn btn-dark btn-sm rounded-pill">
            <i class="bi bi-clipboard-check me-1"></i> Hasil
          </a>
        </div>
      </div>
    </div>
  @empty
    <div class="alert alert-secondary text-center py-4 rounded-3 shadow-sm">
      <i class="bi bi-info-circle me-2"></i> Belum ada tugas. Klik <strong>“Buat Tugas”</strong> untuk menambah.
    </div>
  @endforelse

  <!-- Pagination -->
  <div class="mt-4 d-flex justify-content-center">
    {{ $tasks->links() }}
  </div>
</div>
@endsection
