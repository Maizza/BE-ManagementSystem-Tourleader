@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-11">

      <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-0">
          <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
              <h5 class="mb-0 fw-bold">Konfirmasi Itinerary</h5>
              <small class="text-muted">
                Periksa kembali informasi itinerary sebelum diselesaikan dan disimpan.
              </small>
            </div>
            <span class="badge bg-primary-subtle text-primary-emphasis">
              Langkah Terakhir
            </span>
          </div>
        </div>

        <div class="card-body">

          {{-- Ringkasan utama --}}
          <div class="mb-4 p-3 border rounded bg-light">
            <div class="row gy-2">
              <div class="col-md-6">
                <small class="text-muted d-block">Judul Itinerary</small>
                <h5 class="mb-0">{{ $itinerary->title }}</h5>
              </div>
              <div class="col-md-3">
                <small class="text-muted d-block">Rentang Tanggal</small>
                <strong>
                  {{ $itinerary->start_date ? \Carbon\Carbon::parse($itinerary->start_date)->format('d M Y') : '-' }}
                  —
                  {{ $itinerary->end_date ? \Carbon\Carbon::parse($itinerary->end_date)->format('d M Y') : '-' }}
                </strong>
              </div>
              <div class="col-md-3">
                <small class="text-muted d-block">Total Hari</small>
                <strong>{{ $itinerary->days->count() }} hari</strong>
              </div>
            </div>
          </div>

          {{-- Tour Leaders --}}
          <div class="mb-4">
            <h6 class="fw-bold mb-2">Tour Leader Terkait</h6>

            @if($itinerary->tourLeaders->count())
              <div class="d-flex flex-wrap gap-2">
                @foreach($itinerary->tourLeaders as $tl)
                  <span class="badge rounded-pill bg-secondary">
                    {{ $tl->name }}
                  </span>
                @endforeach
              </div>
            @else
              <p class="text-muted mb-0">Belum ada Tour Leader yang terhubung dengan itinerary ini.</p>
            @endif
          </div>

          {{-- Days & Items --}}
          <div class="mb-3 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0">Rangkuman Hari & Kegiatan</h6>
            <small class="text-muted">
              Pastikan kota, tanggal, dan detail kegiatan sudah benar.
            </small>
          </div>

          @forelse($itinerary->days as $day)
            <div class="mb-3 p-3 border rounded-3 bg-white">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <div>
                  <strong>Day {{ $day->day_number }}</strong>
                  <span class="text-muted">
                    — {{ $day->city ?: 'Kota belum diisi' }}
                  </span>
                  @if($day->date)
                    <span class="text-muted">
                      • {{ $day->date->format('d M Y') }}
                    </span>
                  @endif
                </div>
                <a 
                  href="{{ route('admin.itinerary.fill-items', [$itinerary, $day]) }}" 
                  class="btn btn-sm btn-outline-primary"
                >
                  Edit Kegiatan Hari Ini
                </a>
              </div>

              @if($day->items->count())
                <div class="table-responsive">
                  <table class="table table-sm align-middle mb-0">
                    <thead class="table-light">
                      <tr>
                        <th style="width: 60px;">#</th>
                        <th style="width: 90px;">Jam</th>
                        <th>Judul</th>
                        <th>Deskripsi Singkat</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($day->items as $it)
                        <tr>
                          <td>{{ $it->sequence }}</td>
                          <td>{{ $it->time ? \Carbon\Carbon::parse($it->time)->format('H:i') : '-' }}</td>
                          <td>{{ $it->title ?: 'Belum diisi' }}</td>
                          <td class="text-muted">
                            {{ \Illuminate\Support\Str::limit($it->content, 80) ?: '—' }}
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @else
                <p class="text-muted mb-0 fst-italic">
                  Belum ada kegiatan untuk hari ini.
                </p>
              @endif
            </div>
          @empty
            <p class="text-muted">Belum ada hari yang dibuat untuk itinerary ini.</p>
          @endforelse

          {{-- Aksi akhir --}}
          <form action="{{ route('admin.itinerary.finalize', $itinerary) }}" method="POST" class="mt-4 pt-3 border-top">
            @csrf

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
              <div class="text-muted small">
                Jika masih ada yang ingin diubah, Anda bisa kembali ke pengaturan hari atau kegiatan.
              </div>

              <div class="d-flex gap-2">
                <a href="{{ route('admin.itinerary.fill-days', $itinerary) }}" class="btn btn-light border">
                  &laquo; Kembali Edit Hari
                </a>
                <button type="submit" class="btn btn-success">
                  ✅ Selesaikan & Simpan Itinerary
                </button>
              </div>
            </div>
          </form>

        </div>
      </div>

    </div>
  </div>
</div>
@endsection
