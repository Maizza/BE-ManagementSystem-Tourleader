@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">

      <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-0">
          <h5 class="mb-0 fw-bold">Form 2 — Konfigurasi Jumlah Isi per Hari</h5>
          <small class="text-muted">
            Atur berapa banyak kegiatan yang ingin diisi untuk setiap hari itinerary.
          </small>
        </div>

        <div class="card-body">

          {{-- Error validasi --}}
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

          <form action="{{ route('admin.itinerary.storeForm2') }}" method="POST">
            @csrf

            {{-- Summary draft --}}
            <div class="mb-3 p-3 border rounded bg-light">
              <div class="row">
                <div class="col-12 mb-2">
                  <span class="badge bg-secondary">Ringkasan Draft</span>
                </div>
                <div class="col-12">
                  <p class="mb-1">
                    <strong>Judul:</strong> {{ $draft['title'] }}
                  </p>
                  <p class="mb-1">
                    <strong>Tanggal:</strong> 
                    {{ $draft['start_date'] ?: '-' }} 
                    — 
                    {{ $draft['end_date'] ?: '-' }}
                  </p>
                  <p class="mb-0">
                    <strong>Mode kirim:</strong> 
                    {{ $draft['send_to'] === 'all' ? 'Semua Tourleader' : 'Tourleader Tertentu' }}
                  </p>
                </div>
              </div>
            </div>

            {{-- Tourleader terpilih --}}
            @if($draft['send_to'] === 'selected')
              <div class="mb-4">
                <label class="form-label fw-semibold">
                  Tourleader terpilih (boleh diubah sesuai kebutuhan)
                </label>
                <div class="border rounded p-2" style="max-height:220px; overflow:auto;">
                  @forelse($tourLeaders as $tl)
                    <div class="form-check">
                      <input 
                        class="form-check-input" 
                        type="checkbox" 
                        name="selected_tourleaders[]" 
                        value="{{ $tl->id }}"
                        id="tl_{{ $tl->id }}"
                        {{ in_array($tl->id, $draft['selected_tourleaders'] ?? []) ? 'checked' : '' }}
                      >
                      <label class="form-check-label" for="tl_{{ $tl->id }}">
                        {{ $tl->name }}
                      </label>
                    </div>
                  @empty
                    <p class="text-muted mb-0">Belum ada data Tourleader.</p>
                  @endforelse
                </div>
                <small class="text-muted d-block mt-1">
                  Centang/ubah sesuai TL yang akan menerima itinerary ini.
                </small>
              </div>
            @endif

            <hr>

            {{-- Judul section jumlah isi per hari --}}
            <div class="mb-2">
              <h6 class="fw-bold mb-0">Jumlah Isi per Hari</h6>
              <small class="text-muted">
                Tentukan jumlah kegiatan (0–20) untuk setiap hari. 
                Nanti di Form 3, kamu akan mengisi detail kegiatannya.
              </small>
            </div>

            {{-- Loop hari --}}
            @for($d = 1; $d <= $draft['days_count']; $d++)
              <div class="mb-3 p-3 border rounded-3 bg-white">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <strong>Day {{ $d }}</strong>
                  <span class="badge bg-light text-muted">
                    Hari ke-{{ $d }}
                  </span>
                </div>
                <div class="row align-items-center g-2">
                  <div class="col-6 col-sm-4">
                    <label class="form-label mb-0">Jumlah isi</label>
                  </div>
                  <div class="col-6 col-sm-4">
                    <select 
                      name="days[{{ $d-1 }}][item_count]" 
                      class="form-select form-select-sm"
                    >
                      @for($i = 0; $i <= 20; $i++)
                        <option value="{{ $i }}">
                          {{ $i }} kegiatan
                        </option>
                      @endfor
                    </select>
                  </div>
                </div>
              </div>
            @endfor

            {{-- Tombol aksi --}}
            <div class="d-flex justify-content-between align-items-center mt-3">
              <a href="{{ url()->previous() }}" class="btn btn-light border">
                Kembali
              </a>
              <button type="submit" class="btn btn-primary">
                Buat Itinerary &amp; Lanjut ke Form 3
              </button>
            </div>

          </form>
        </div>
      </div>

    </div>
  </div>
</div>
@endsection
