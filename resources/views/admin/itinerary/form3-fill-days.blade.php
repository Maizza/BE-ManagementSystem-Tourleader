@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-10">

      <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-0">
          <h5 class="mb-0 fw-bold">Form 3 — Isi Informasi Setiap Hari</h5>
          <small class="text-muted">Lengkapi kota, tanggal, dan lanjutkan ke detail kegiatan.</small>
        </div>

        <div class="card-body">

          <form action="{{ route('admin.itinerary.save-days', $itinerary) }}" method="POST">
            @csrf

            @foreach($itinerary->days as $day)
              <div class="mb-4 p-3 border rounded bg-light">

                {{-- Title --}}
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <h5 class="mb-0">Day {{ $day->day_number }}</h5>
                  <span class="badge bg-secondary">Hari Ke {{ $day->day_number }}</span>
                </div>

                <input type="hidden" name="days[{{ $day->id }}][id]" value="{{ $day->id }}">

                {{-- Kota --}}
                <div class="mb-3">
                  <div class="d-flex justify-content-between align-items-center">
                    <label class="form-label fw-semibold mb-0">Kota</label>

                    <a href="{{ route('admin.itinerary.kota.index') }}" 
                       target="_blank" 
                       class="small text-decoration-underline">
                      Kelola daftar kota
                    </a>
                  </div>

                  <select 
                    class="form-select"
                    name="days[{{ $day->id }}][city]"
                  >
                    <option value="">-- Pilih Kota --</option>

                    @foreach($cities as $city)
                      <option 
                        value="{{ $city->name }}"
                        {{ old("days.{$day->id}.city", $day->city) == $city->name ? 'selected' : '' }}
                      >
                        {{ $city->name }}
                      </option>
                    @endforeach
                  </select>
                </div>

                {{-- Tanggal --}}
                <div class="mb-3">
                  <label class="form-label fw-semibold">Tanggal</label>
                  <input 
                    type="date" 
                    class="form-control"
                    name="days[{{ $day->id }}][date]" 
                    value="{{ old("days.{$day->id}.date", $day->date ? $day->date->format('Y-m-d') : '') }}"
                  >
                </div>

                {{-- List Items --}}
                <div class="mb-2">
                  <strong class="d-block mb-1">Daftar Kegiatan ({{ $day->items->count() }})</strong>

                  @if($day->items->count() > 0)
                    <ul class="list-group mb-2">
                      @foreach($day->items as $item)
                        <li class="list-group-item d-flex justify-content-between">
                          <div>
                            <strong>Isi ke-{{ $item->sequence }}</strong><br>
                            <small class="text-muted">{{ $item->title ?? 'Belum diisi judul kegiatan' }}</small>
                          </div>
                          <span class="badge bg-light text-muted">#{{ $item->sequence }}</span>
                        </li>
                      @endforeach
                    </ul>
                  @else
                    <p class="text-muted fst-italic">Belum ada kegiatan untuk hari ini.</p>
                  @endif
                </div>

              </div>
            @endforeach

            {{-- Tombol Aksi --}}
            <div class="d-flex justify-content-between">
              <a href="{{ url()->previous() }}" class="btn btn-light border">
                Kembali
              </a>

              <button type="submit" class="btn btn-primary">
                Lanjut Isi Kegiatan
              </button>
            </div>

          </form>

        </div>
      </div>
    </div>
  </div>
</div>
@endsection
