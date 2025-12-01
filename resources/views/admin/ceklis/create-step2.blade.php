@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width: 820px;">
  <div class="card shadow-sm border-0 rounded-4">
    <div class="card-body p-4">

      <h4 class="fw-bold mb-3 text-primary d-flex align-items-center">
        <i class="bi bi-ui-checks-grid me-2"></i> 
        Form Soal Ceklis — Langkah 2
      </h4>
      <hr class="mb-4 mt-2">

      {{-- Info dari langkah 1 --}}
      <div class="alert alert-info rounded-3 shadow-sm small">
        <div class="fw-semibold mb-1">
          <i class="bi bi-clipboard-data me-1"></i> Informasi Tugas
        </div>
        <ul class="mb-0 ps-3">
          <li><b>Judul:</b> {{ $s1['title'] }}</li>
          <li><b>Dibuka:</b> {{ \Carbon\Carbon::parse($s1['opens_at'])->format('d M Y · H:i') }}</li>
          <li><b>Ditutup:</b> {{ \Carbon\Carbon::parse($s1['closes_at'])->format('d M Y · H:i') }}</li>
        </ul>
      </div>

      {{-- Error handling --}}
      @if ($errors->any())
        <div class="alert alert-danger rounded-3 shadow-sm">
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

      {{-- Form step 2 --}}
      <form method="POST" action="{{ route('admin.ceklis.store.step2') }}">
        @csrf

        {{-- Info kloter --}}
        <div class="alert alert-secondary small rounded-3 shadow-sm">
          <i class="bi bi-info-circle me-1"></i>
          Kloter akan otomatis diisi berdasarkan profil <b>Tour Leader</b> yang terdaftar di sistem.
        </div>

        {{-- Input soal --}}
        <div class="mb-4">
          <h6 class="fw-semibold text-dark mb-3">
            <i class="bi bi-list-task me-1 text-secondary"></i> Daftar Soal
          </h6>

          @for ($i = 0; $i < (int) $s1['question_count']; $i++)
            <div class="mb-3">
              <label class="form-label fw-semibold">
                Soal {{ $i + 1 }}
              </label>
              <input name="questions[]" class="form-control rounded-3 shadow-sm"
                     value="{{ old('questions.'.$i) }}"
                     placeholder="Masukkan pertanyaan untuk tugas ini..." required>
            </div>
          @endfor
        </div>

        {{-- Pilihan Tour Leader (jika target = tertentu) --}}
        @if ($s1['target'] === 'tertentu')
          <div class="mt-4">
            <label class="form-label fw-semibold">
              <i class="bi bi-person-lines-fill me-1 text-secondary"></i> Pilih Tour Leader
            </label>

            <div class="border rounded-3 shadow-sm p-3 bg-light" style="max-height: 320px; overflow-y: auto;">
              @forelse ($allTourLeaders as $tl)
                <div class="form-check mb-2">
                  <input class="form-check-input"
                         type="checkbox"
                         name="tourleader_ids[]"
                         id="tl_{{ $tl->id }}"
                         value="{{ $tl->id }}"
                         @checked(collect(old('tourleader_ids'))->contains($tl->id))>
                  <label class="form-check-label" for="tl_{{ $tl->id }}">
                    <span class="fw-semibold">{{ $tl->name }}</span>
                    <span class="text-muted small d-block ms-4">
                      {{ $tl->kloter->nama ?? 'Tidak ada kloter' }}
                    </span>
                  </label>
                </div>
              @empty
                <p class="text-muted small mb-0">Belum ada Tour Leader terdaftar.</p>
              @endforelse
            </div>

            <div class="form-text mt-1">
              Centang satu atau lebih Tour Leader yang ingin dikirimi tugas ini.
            </div>
          </div>
        @endif

        {{-- Tombol aksi --}}
        <div class="d-flex justify-content-between mt-4">
          <a href="{{ route('admin.ceklis.create.step1') }}" class="btn btn-outline-secondary btn-lg px-4 rounded-3 shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali
          </a>

          <button class="btn btn-primary btn-lg px-4 rounded-3 shadow-sm">
            Selesai <i class="bi bi-check2 ms-1"></i>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
