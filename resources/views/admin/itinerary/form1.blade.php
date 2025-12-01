@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">

      <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
          <div>
            <h5 class="mb-0 fw-bold">Form 1 — Buat Itinerary</h5>
            <small class="text-muted">Lengkapi data umum sebelum mengisi detail per hari.</small>
          </div>
        </div>

        <div class="card-body">
          
          {{-- Tampilkan error validasi jika ada --}}
          @if ($errors->any())
            <div class="alert alert-danger">
              <strong>Ups, ada yang perlu dicek lagi:</strong>
              <ul class="mb-0 mt-1">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form action="{{ route('admin.itinerary.storeForm1') }}" method="POST">
            @csrf   

            {{-- Judul --}}
            <div class="mb-3">
              <label class="form-label fw-semibold">Judul Itinerary <span class="text-danger">*</span></label>
              <input 
                type="text" 
                name="title" 
                class="form-control @error('title') is-invalid @enderror"
                value="{{ old('title') }}" 
                placeholder="Contoh: Umrah Plus Istanbul 9 Hari"
                required
              >
              @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
              @else
                <small class="text-muted">Silakan masukan judul Itinerary nya</small>
              @enderror
            </div>

            {{-- Tanggal Mulai & Selesai --}}
            <div class="mb-3">
              <label class="form-label fw-semibold">Rentang Tanggal Keberangkatan</label>
              <div class="row g-2">
                <div class="col-md-6">
                  <div class="form-floating">
                    <input 
                      type="date" 
                      name="start_date" 
                      id="start_date"
                      class="form-control @error('start_date') is-invalid @enderror"
                      value="{{ old('start_date') }}"
                    >
                    <label for="start_date">Tanggal Mulai</label>
                    @error('start_date')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-floating">
                    <input 
                      type="date" 
                      name="end_date" 
                      id="end_date"
                      class="form-control @error('end_date') is-invalid @enderror"
                      value="{{ old('end_date') }}"
                    >
                    <label for="end_date">Tanggal Selesai</label>
                    @error('end_date')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
              </div>
              <small class="text-muted d-block mt-1">
                Silakan masukan tanggal mulai selesai Keberangkatan
              </small>
            </div>

            {{-- Kirim ke --}}
            <div class="mb-3">
              <label class="form-label fw-semibold">Kirim Itinerary ke</label>
              <select 
                name="send_to" 
                id="send_to" 
                class="form-select @error('send_to') is-invalid @enderror"
              >
                <option value="all" {{ old('send_to')=='all' ? 'selected':'' }}>Semua Tourleader</option>
                <option value="selected" {{ old('send_to')=='selected' ? 'selected':'' }}>Tourleader Tertentu</option>
              </select>
              @error('send_to')
                <div class="invalid-feedback">{{ $message }}</div>
              @else
                <small class="text-muted">Silakan pilih mau kirim kesiapa</small>
              @enderror
            </div>

            {{-- Pilih Tourleader --}}
            <div id="tl_box" class="mb-3" style="display: none;">
              <label class="form-label fw-semibold">Pilih Tourleader</label>
              <div class="border rounded p-2" style="max-height:220px; overflow:auto;">
                @forelse($tourLeaders as $tl)
                  <div class="form-check">
                    <input 
                      class="form-check-input" 
                      type="checkbox" 
                      name="selected_tourleaders[]" 
                      value="{{ $tl->id }}"
                      id="tl_{{ $tl->id }}"
                      {{ in_array($tl->id, old('selected_tourleaders', [])) ? 'checked' : '' }}
                    >
                    <label class="form-check-label" for="tl_{{ $tl->id }}">
                      {{ $tl->name }}
                    </label>
                  </div>
                @empty
                  <p class="text-muted mb-0">Belum ada data Tourleader.</p>
                @endforelse
              </div>
              <small class="text-muted d-block mt-1">Silakan centang nama tourleader yang mau di kirim</small>
            </div>

            {{-- Jumlah Hari --}}
            <div class="mb-4">
              <label class="form-label fw-semibold">Jumlah Hari Itinerary</label>
              <div class="input-group" style="max-width: 200px;">
                <input 
                  type="number" 
                  name="days_count" 
                  min="1" max="30" 
                  class="form-control @error('days_count') is-invalid @enderror"
                  value="{{ old('days_count', 1) }}"
                >
                <span class="input-group-text">hari</span>
                @error('days_count')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>
              <small class="text-muted">Silakan pilih jumlah hari nya </small>
            </div>

            {{-- Tombol --}}
            <div class="d-flex justify-content-between align-items-center">
              <a href="{{ url()->previous() }}" class="btn btn-light border">
                Batal
              </a>
              <button type="submit" class="btn btn-primary">
                Lanjut ke Form 2
              </button>
            </div>

          </form>
        </div>
      </div>

    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const sel = document.getElementById('send_to');
  const box = document.getElementById('tl_box');

  function toggle() {
    box.style.display = sel.value === 'selected' ? 'block' : 'none';
  }

  sel.addEventListener('change', toggle);
  toggle(); // initial state
});
</script>
@endsection
