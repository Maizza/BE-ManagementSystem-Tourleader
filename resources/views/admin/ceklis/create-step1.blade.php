@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width: 880px;">
  <div class="card shadow-sm border-0 rounded-4">
    <div class="card-body p-4">

      <h4 class="fw-bold mb-3 text-primary d-flex align-items-center">
        <i class="bi bi-clipboard-check me-2"></i> 
        Form Tugas Ceklis — Langkah 1
      </h4>
      <hr class="mb-4 mt-2">

      @if ($errors->any())
        <div class="alert alert-danger rounded-3">
          <div class="fw-semibold mb-2">
            <i class="bi bi-exclamation-triangle me-1"></i> Ada kesalahan:
          </div>
          <ul class="mb-0 small">
            @foreach ($errors->all() as $e)
              <li>{{ $e }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ route('admin.ceklis.store.step1') }}">
        @csrf

        {{-- Judul --}}
        <div class="mb-4">
          <label class="form-label fw-semibold">
            <i class="bi bi-pencil-square me-1 text-secondary"></i> Judul Tugas
          </label>
          <input name="title" class="form-control form-control-lg rounded-3 shadow-sm"
                 value="{{ old('title') }}" placeholder="Masukkan judul tugas..." required>
        </div>

        {{-- Info kloter --}}
        <div class="alert alert-info small rounded-3 shadow-sm">
          <i class="bi bi-info-circle me-1"></i>
          Kloter akan otomatis diambil dari profil Tour Leader (tidak perlu dipilih manual).
        </div>
        <input type="hidden" name="kloter_count" value="1">

        <div class="row g-4">
          {{-- Jumlah tugas --}}
          <div class="col-md-4">
            <label class="form-label fw-semibold">
              <i class="bi bi-list-check me-1 text-secondary"></i> Jumlah Soal
            </label>
            <input type="number" min="1" max="50" name="question_count"
                   class="form-control rounded-3 shadow-sm"
                   value="{{ old('question_count', 3) }}" required>
          </div>

          {{-- Waktu buka --}}
          <div class="col-md-4">
            <label class="form-label fw-semibold">
              <i class="bi bi-clock-history me-1 text-secondary"></i> Waktu Dibuka
            </label>
            <input type="datetime-local" name="opens_at"
                   class="form-control rounded-3 shadow-sm"
                   value="{{ old('opens_at') }}" required>
          </div>

          {{-- Waktu tutup --}}
          <div class="col-md-4">
            <label class="form-label fw-semibold">
              <i class="bi bi-lock me-1 text-secondary"></i> Waktu Ditutup
            </label>
            <input type="datetime-local" name="closes_at"
                   class="form-control rounded-3 shadow-sm"
                   value="{{ old('closes_at') }}" required>
          </div>
        </div>

        {{-- Target --}}
        <div class="mt-4">
          <label class="form-label fw-semibold">
            <i class="bi bi-people me-1 text-secondary"></i> Kirim ke
          </label>
          <select name="target" id="target" class="form-select rounded-3 shadow-sm" required>
            <option value="semua">Semua Tour Leader</option>
            <option value="tertentu">Tour Leader Tertentu</option>
          </select>
        </div>

        {{-- Tombol Aksi --}}
        <div class="d-flex justify-content-between align-items-center mt-5">
          <a href="{{ route('admin.ceklis.index') }}" 
             class="btn btn-outline-secondary btn-lg px-4 rounded-3 shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali
          </a>

          <button class="btn btn-primary btn-lg px-4 rounded-3 shadow-sm">
            Lanjut <i class="bi bi-arrow-right-short ms-1"></i>
          </button>
        </div>
      </form>

    </div>
  </div>
</div>
@endsection
