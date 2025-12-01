@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-0">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h5 class="mb-0 fw-bold">Form 4 — Isi Kegiatan Semua Hari</h5>
              <small class="text-muted">
                Lengkapi jam, judul, dan deskripsi untuk setiap kegiatan pada seluruh hari di itinerary ini.
              </small>
            </div>
            <span class="badge bg-primary-subtle text-primary-emphasis d-none d-md-inline">
              Langkah 4 dari 4
            </span>
          </div>
        </div>

        <div class="card-body">

          {{-- Ringkasan singkat itinerary --}}
          <div class="mb-4 p-3 border rounded bg-light">
            <div class="row gy-1">
              <div class="col-md-6">
                <small class="text-muted d-block">Itinerary</small>
                <strong>{{ $itinerary->title }}</strong>
              </div>
              <div class="col-md-6">
                <small class="text-muted d-block">Total Hari</small>
                <strong>{{ $days->count() }} Day</strong>
              </div>
            </div>
          </div>

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

          {{-- Form simpan semua kegiatan --}}
          <form action="{{ route('admin.itinerary.save-items', $itinerary) }}" method="POST">
            @csrf

            @foreach($days as $day)
              @php
                $dayAccordionId = 'day_'.$day->id;
              @endphp

              <div class="mb-4 border rounded">
                {{-- Header per Day --}}
                <div class="p-3 border-bottom bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
                  <div>
                    <small class="text-muted d-block">Hari</small>
                    <strong>Day {{ $day->day_number }}</strong>
                  </div>
                  <div>
                    <small class="text-muted d-block">Kota / Tanggal</small>
                    <strong>
                      {{ $day->city ?: '-' }}
                      @if($day->date)
                        — {{ $day->date->format('d M Y') }}
                      @endif
                    </strong>
                  </div>
                </div>

                {{-- Accordion kegiatan per Day --}}
                <div class="p-3 bg-light">
                  <div class="accordion accordion-flush" id="accordionItems-{{ $dayAccordionId }}">
                    @forelse($day->items as $item)
                      @php
                        $accordionId = 'item_'.$item->id;
                        $inputTime   = old("items.{$item->id}.time", $item->time_for_input ?? $item->time ?? '');
                        $inputTitle  = old("items.{$item->id}.title", $item->title);
                        $inputDesc   = old("items.{$item->id}.content", $item->content);
                      @endphp

                      <div class="accordion-item border rounded mb-3">
                        <h2 class="accordion-header" id="heading-{{ $accordionId }}">
                          <button
                            class="accordion-button {{ $loop->first ? '' : 'collapsed' }}"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#collapse-{{ $accordionId }}"
                            aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                            aria-controls="collapse-{{ $accordionId }}"
                          >
                            <div class="d-flex flex-column flex-md-row w-100 justify-content-between">
                              <div>
                                <span class="fw-semibold">Isi ke-{{ $item->sequence }}</span>
                                <small class="d-block text-muted">
                                  {{ $inputTime ?: 'Jam belum diisi' }}
                                  @if($inputTitle)
                                    • {{ \Illuminate\Support\Str::limit($inputTitle, 40) }}
                                  @endif
                                </small>
                              </div>
                              <span class="badge bg-secondary align-self-start align-self-md-center mt-2 mt-md-0">
                                #{{ $item->sequence }}
                              </span>
                            </div>
                          </button>
                        </h2>

                        <div
                          id="collapse-{{ $accordionId }}"
                          class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                          aria-labelledby="heading-{{ $accordionId }}"
                          data-bs-parent="#accordionItems-{{ $dayAccordionId }}"
                        >
                          <div class="accordion-body bg-light">

                            <input type="hidden" name="items[{{ $item->id }}][id]" value="{{ $item->id }}">

                            {{-- Jam --}}
                            <div class="mb-3">
                              <label class="form-label fw-semibold">Jam (HH:MM)</label>
                              <input
                                type="time"
                                class="form-control"
                                name="items[{{ $item->id }}][time]"
                                value="{{ $inputTime }}"
                              >
                              <small class="text-muted">
                                Contoh: 07:30, 10:15, 21:00 — opsional tapi membantu TL membaca alur kegiatan.
                              </small>
                            </div>

                            {{-- Judul --}}
                            <div class="mb-3">
                              <label class="form-label fw-semibold">Judul Kegiatan</label>
                              <input
                                type="text"
                                class="form-control"
                                name="items[{{ $item->id }}][title]"
                                value="{{ $inputTitle }}"
                                placeholder="Contoh: Tawaf, Ziarah Raudhah, Check-in Hotel, Briefing Jamaah"
                              >
                            </div>

                            {{-- Deskripsi --}}
                            <div class="mb-2">
                              <label class="form-label fw-semibold">Deskripsi / Catatan</label>
                              <textarea
                                name="items[{{ $item->id }}][content]"
                                rows="3"
                                class="form-control"
                                placeholder="Tambahkan detail kegiatan, titik kumpul, dresscode, atau catatan penting lainnya."
                              >{{ $inputDesc }}</textarea>
                            </div>

                          </div>
                        </div>
                      </div>
                    @empty
                      <p class="text-muted mb-0">
                        Belum ada item kegiatan untuk hari ini.
                      </p>
                    @endforelse
                  </div>
                </div>
              </div>
            @endforeach

            {{-- Tombol aksi global --}}
            <div class="mt-4 pt-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
              <a href="{{ route('admin.itinerary.fill-days', $itinerary) }}" class="btn btn-light border">
                &laquo; Kembali ke Daftar Hari
              </a>

              <div class="d-flex gap-2">
                <a href="{{ route('admin.itinerary.confirm', $itinerary) }}" class="btn btn-outline-secondary">
                  Lihat Ringkasan Itinerary
                </a>
                <button type="submit" class="btn btn-primary">
                  Simpan Semua Kegiatan & Lanjut ke Ringkasan
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
