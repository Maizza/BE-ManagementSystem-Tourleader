@extends('layouts.app')

@section('content')
@php($jumlah = (int)($s1['question_count'] ?? 0))

<div class="container py-5">
  <div class="card shadow-lg border-0 rounded-4">
    <div class="card-body p-4">
      {{-- Header --}}
      <div class="mb-4">
        <h4 class="fw-bold mb-1 text-primary">
          <i class="bi bi-list-check me-2"></i>Form Tugas – Langkah 2
        </h4>
        <p class="text-muted mb-0">
          Isi <strong>{{ $jumlah }}</strong> soal untuk tugas: 
          <span class="fw-semibold text-dark">{{ $s1['title'] }}</span>
        </p>
      </div>

      <form method="POST" action="{{ route('admin.tasks.store.step2') }}" class="row g-4">
        @csrf

        {{-- Hidden data dari Step 1 --}}
        <input type="hidden" name="title" value="{{ $s1['title'] }}">
        <input type="hidden" name="question_count" value="{{ $s1['question_count'] }}">
        <input type="hidden" name="opens_at" value="{{ $s1['opens_at'] }}">
        <input type="hidden" name="closes_at" value="{{ $s1['closes_at'] }}">
        <input type="hidden" name="target_type" value="{{ $s1['target_type'] }}">

        {{-- Soal --}}
        <div class="col-12">
          <div class="border-start border-4 border-primary ps-3 mb-3">
            <h5 class="fw-semibold mb-1">Daftar Soal</h5>
            <p class="text-muted small mb-0">Lengkapi semua pertanyaan di bawah ini.</p>
          </div>
        </div>

        @for($i=0;$i<$jumlah;$i++)
          <div class="col-12">
            <label class="form-label fw-semibold">Soal {{ $i+1 }}</label>
            <input 
              name="questions[{{ $i }}]" 
              value="{{ old("questions.$i") }}" 
              class="form-control form-control-lg rounded-3 shadow-sm" 
              placeholder="Masukkan soalnya…" 
              required>
            @error("questions.$i")
              <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
          </div>
        @endfor

        {{-- Jika target_type = specific --}}
        @if(($s1['target_type'] ?? '') === 'specific')
          <div class="col-12 mt-3">
            <div class="border-start border-4 border-info ps-3 mb-3">
              <h5 class="fw-semibold mb-1">Pilih Tour Leader</h5>
              <p class="text-muted small mb-0">Centang tour leader yang ingin dikirimkan tugas ini.</p>
            </div>

            <div class="border rounded-4 p-3 shadow-sm" style="max-height: 300px; overflow-y: auto;">
              @forelse($tourLeaders as $tl)
                <div class="form-check mb-2">
                  <input class="form-check-input" 
                         type="checkbox" 
                         name="tour_leaders[]" 
                         value="{{ $tl->id }}" 
                         id="tl{{ $tl->id }}">
                  <label class="form-check-label fw-medium" for="tl{{ $tl->id }}">
                    {{ $tl->nama ?? $tl->name ?? 'Nama tidak tersedia' }}
                  </label>
                </div>
              @empty
                <p class="text-muted text-center mb-0">Belum ada tour leader terdaftar.</p>
              @endforelse
            </div>
          </div>
        @endif

        {{-- Tombol Aksi --}}
        <div class="col-12 d-flex justify-content-between align-items-center mt-4">
          <a href="{{ route('admin.tasks.create.step1') }}" class="btn btn-outline-secondary px-4 py-2 rounded-3">
            <i class="bi bi-arrow-left-circle me-1"></i> Kembali
          </a>
          <button class="btn btn-primary px-4 py-2 rounded-3 shadow-sm">
            <i class="bi bi-check-circle me-1"></i> Selesai
          </button>
        </div>

      </form>
    </div>
  </div>
</div>
@endsection
