@extends('layouts.app')

@section('content')
<div class="container py-5">
  {{-- Header Section --}}
  <div class="card shadow-sm border-0 rounded-4 mb-4">
    <div class="card-body d-flex flex-wrap justify-content-between align-items-start p-4">
      <div>
        <h4 class="fw-bold text-primary mb-2">
          <i class="bi bi-journal-text me-2"></i>{{ $task->title }}
        </h4>

        <ul class="list-unstyled text-muted small mb-0">
          <li><i class="bi bi-calendar-check me-1 text-success"></i>
            Dibuka : {{ $task->opens_at->format('l, d M Y · H:i') }}
          </li>
          <li><i class="bi bi-calendar-x me-1 text-danger"></i>
            Ditutup : {{ $task->closes_at->format('l, d M Y · H:i') }}
          </li>
          <li><i class="bi bi-list-ol me-1 text-primary"></i>
            Jumlah Soal : <strong>{{ $task->question_count }}</strong>
          </li>
        </ul>
      </div>

      <div class="d-flex flex-wrap gap-2 mt-3 mt-md-0">
        <a href="{{ route('admin.tasks.result', $task) }}" class="btn btn-primary px-4 shadow-sm">
          <i class="bi bi-graph-up me-1"></i> Lihat Hasil
        </a>
        <a href="{{ route('admin.tasks.index') }}" class="btn btn-outline-secondary px-4">
          <i class="bi bi-arrow-left-circle me-1"></i> Kembali
        </a>
      </div>
    </div>
  </div>

  {{-- Question List --}}
  <div class="card shadow-sm border-0 rounded-4">
    <div class="card-header bg-light border-0 rounded-top-4 py-3">
      <h6 class="fw-semibold mb-0 text-dark">
        <i class="bi bi-question-circle me-2 text-primary"></i>Daftar Soal
      </h6>
    </div>
    <div class="card-body p-4">
      @if($task->questions->count())
        <ol class="list-group list-group-numbered">
          @foreach ($task->questions as $q)
            <li class="list-group-item border-0 border-bottom py-3">
              <span class="fw-medium">{{ $q->question_text }}</span>
            </li>
          @endforeach
        </ol>
      @else
        <div class="text-muted text-center py-4">
          <i class="bi bi-exclamation-circle me-1"></i> Belum ada soal untuk tugas ini.
        </div>
      @endif
    </div>
  </div>
</div>
@endsection
