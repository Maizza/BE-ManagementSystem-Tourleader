@extends('layouts.app')

@section('content')
<div class="container py-5">
  <div class="card shadow-lg border-0 rounded-4">
    <div class="card-body p-4">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">
          <i class="bi bi-clipboard-check me-2 text-primary"></i>Form Tugas – Langkah 1
        </h4>
        <a href="{{ route('admin.tasks.index') }}" class="btn btn-sm btn-outline-secondary">
          <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
      </div>

      <form method="POST" action="{{ route('admin.tasks.store.step1') }}" class="row g-4">
        @csrf

        {{-- Judul --}}
        <div class="col-12">
          <label class="form-label fw-semibold">Judul Tugas</label>
          <input name="title" value="{{ old('title') }}" class="form-control form-control-lg rounded-3 shadow-sm" placeholder="Masukkan judul tugas..." required>
          @error('title')
            <div class="text-danger small mt-1">{{ $message }}</div>
          @enderror
        </div>

        {{-- Jumlah Soal --}}
        <div class="col-md-6">
          <label class="form-label fw-semibold">Jumlah Soal</label>
          <select name="question_count" class="form-select form-select-lg rounded-3 shadow-sm" required>
            <option value="" disabled selected>Pilih jumlah</option>
            @for($i=1;$i<=20;$i++)
              <option value="{{ $i }}" @selected(old('question_count')==$i)>{{ $i }}</option>
            @endfor
          </select>
          @error('question_count')
            <div class="text-danger small mt-1">{{ $message }}</div>
          @enderror
        </div>

        {{-- Waktu Dibuka --}}
        <div class="col-md-3">
          <label class="form-label fw-semibold">Waktu Dibuka</label>
          <input type="datetime-local" name="opens_at" value="{{ old('opens_at') }}" class="form-control rounded-3 shadow-sm" required>
          @error('opens_at')
            <div class="text-danger small mt-1">{{ $message }}</div>
          @enderror
        </div>

        {{-- Waktu Ditutup --}}
        <div class="col-md-3">
          <label class="form-label fw-semibold">Waktu Ditutup</label>
          <input type="datetime-local" name="closes_at" value="{{ old('closes_at') }}" class="form-control rounded-3 shadow-sm" required>
          @error('closes_at')
            <div class="text-danger small mt-1">{{ $message }}</div>
          @enderror
        </div>

        {{-- Kirim ke siapa --}}
        <div class="col-md-6">
          <label class="form-label fw-semibold">Kirim ke Siapa</label>
          <select name="target_type" id="target_type" class="form-select form-select-lg rounded-3 shadow-sm" required>
            <option value="" disabled selected>Pilih tujuan</option>
            <option value="all" @selected(old('target_type')=='all')>Semua Tour Leader</option>
            <option value="specific" @selected(old('target_type')=='specific')>Tour Leader Tertentu</option>
          </select>
          @error('target_type')
            <div class="text-danger small mt-1">{{ $message }}</div>
          @enderror
        </div>

        {{-- Tombol --}}
        <div class="col-12 d-flex justify-content-end gap-2 mt-3">
          <a href="{{ route('admin.tasks.index') }}" class="btn btn-outline-secondary px-4 py-2 rounded-3">
            <i class="bi bi-x-circle me-1"></i> Batal
          </a>
          <button class="btn btn-primary px-4 py-2 rounded-3 shadow-sm">
            <i class="bi bi-arrow-right-circle me-1"></i> Lanjut
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
