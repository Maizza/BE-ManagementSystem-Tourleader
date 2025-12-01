@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width: 850px">
  <div class="card shadow-sm border-0 rounded-4">
    <div class="card-body p-4">

      {{-- Header --}}
      <h4 class="fw-bold mb-4 text-primary">
        <i class="bi bi-clipboard-check me-2 text-success"></i>
        Konfirmasi Tugas Ceklis — <span class="text-dark">Langkah 3</span>
      </h4>

      {{-- Info Umum --}}
      <div class="alert alert-info border-0 shadow-sm rounded-3">
        <div class="d-flex align-items-center mb-2">
          <i class="bi bi-info-circle-fill me-2"></i>
          <strong>Informasi Tugas</strong>
        </div>
        <ul class="mb-0 small">
          <li><b>Judul:</b> {{ $s1['title'] }}</li>
          <li><b>Jumlah Soal:</b> {{ $s1['question_count'] }}</li>
          <li><b>Dibuka:</b> {{ \Carbon\Carbon::parse($s1['opens_at'])->format('d M Y · H:i') }}</li>
          <li><b>Ditutup:</b> {{ \Carbon\Carbon::parse($s1['closes_at'])->format('d M Y · H:i') }}</li>
        </ul>
      </div>

      {{-- Tour Leader & Kloter --}}
      <div class="card border-0 shadow-sm mb-4 rounded-4">
        <div class="card-header bg-gradient fw-semibold text-white rounded-top-4" 
             style="background: linear-gradient(90deg, #1A365D, #2C5282);">
          <i class="bi bi-people-fill me-2"></i>Tour Leader & Kloter Otomatis
        </div>
        <ul class="list-group list-group-flush">
          @forelse ($selectedTourLeaders as $tl)
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <div>
                <i class="bi bi-person-circle me-2 text-primary"></i>{{ $tl->name }}
              </div>
              <span class="badge bg-light text-dark border rounded-pill px-3">
                {{ $tl->kloter->nama ?? 'Tidak ada kloter' }}
              </span>
            </li>
          @empty
            <li class="list-group-item text-muted text-center py-3">
              <i class="bi bi-exclamation-circle me-2"></i>Belum ada Tour Leader dipilih.
            </li>
          @endforelse
        </ul>
      </div>

      {{-- Daftar Soal --}}
      <div class="card border-0 shadow-sm mb-4 rounded-4">
        <div class="card-header bg-gradient text-white fw-semibold rounded-top-4"
             style="background: linear-gradient(90deg, #2B6CB0, #3182CE);">
          <i class="bi bi-question-circle-fill me-2"></i>Daftar Soal Ceklis
        </div>
        <ul class="list-group list-group-flush">
          @foreach ($questions as $i => $q)
            <li class="list-group-item">
              <span class="fw-semibold text-primary">Soal {{ $i + 1 }}:</span>
              <span class="ms-1">{{ $q }}</span>
            </li>
          @endforeach
        </ul>
      </div>

      {{-- Konfirmasi Akhir --}}
      <form method="POST" action="{{ route('admin.ceklis.store.final') }}">
        @csrf
        <input type="hidden" name="step1" value="{{ json_encode($s1) }}">
        <input type="hidden" name="questions" value="{{ json_encode($questions) }}">
        <input type="hidden" name="tourleader_ids" value="{{ json_encode($selectedTourLeaders->pluck('id')) }}">

        <div class="alert alert-warning small border-0 shadow-sm rounded-3">
          <i class="bi bi-exclamation-triangle-fill me-2 text-warning"></i>
          Pastikan semua data sudah benar sebelum menekan tombol <b>Simpan Tugas</b>.
        </div>

        <div class="d-flex justify-content-end mt-3">
          <a href="{{ route('admin.ceklis.create.step2') }}" class="btn btn-outline-secondary me-2 rounded-3">
            <i class="bi bi-arrow-left"></i> Kembali
          </a>
          <button class="btn btn-success rounded-3 shadow-sm">
            Simpan Tugas <i class="bi bi-check2 ms-1"></i>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
