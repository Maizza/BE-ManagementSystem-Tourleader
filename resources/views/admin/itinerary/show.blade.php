@extends('layouts.app')

@section('content')
<div class="container" style="max-width:1150px">
    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h3 class="fw-bold mb-1 text-dark">{{ $itinerary->title }}</h3>

            <div class="text-muted small d-flex flex-wrap gap-3 mt-1">
                <span>
                    <i class="far fa-calendar-alt me-1"></i>
                    {{ $itinerary->start_date?->format('d M Y') }} –
                    {{ $itinerary->end_date?->format('d M Y') }}
                </span>

                <span>
                    <i class="fas fa-user-tie me-1"></i>
                    TL: {{ $itinerary->tour_leader_name }}
                </span>

                <span>
                    <i class="fas fa-list-ol me-1"></i>
                    {{ $itinerary->days->count() }} Hari
                </span>
            </div>
        </div>

        {{-- BUTTONS --}}
        <div class="d-flex gap-2">
            <a href="{{ route('admin.itinerary.edit', $itinerary) }}"
               class="btn btn-success px-4 shadow-sm rounded-pill">
                <i class="fas fa-edit me-1"></i> Edit Kegiatan
            </a>

            <a href="{{ route('admin.itinerary.index') }}" 
               class="btn btn-outline-secondary px-4 shadow-sm rounded-pill">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    @if(session('ok'))
        <div class="alert alert-success shadow-sm rounded-3">{{ session('ok') }}</div>
    @endif

    {{-- SUMMARY CARDS --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <div class="text-uppercase text-muted small mb-1">Judul</div>
                    <div class="fw-semibold fs-5">{{ $itinerary->title }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <div class="text-uppercase text-muted small mb-1">Periode</div>
                    <div class="fw-semibold">
                        {{ $itinerary->start_date?->format('d M Y') }}
                        –
                        {{ $itinerary->end_date?->format('d M Y') }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <div class="text-uppercase text-muted small mb-1">Tour Leader</div>
                    <div class="fw-semibold">
                        {{ $itinerary->tourLeaders->pluck('name')->join(', ') ?: '-' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- DAYS LIST --}}
    @forelse($itinerary->days as $day)
        <div class="card border-0 shadow-sm mb-4 rounded-4">

            {{-- DAY HEADER --}}
            <div class="card-header bg-white border-0 py-3 rounded-top-4 
                d-flex justify-content-between align-items-center">

                <div>
                    <strong class="text-primary">Day {{ $day->day_number }}</strong>

                    @if($day->date)
                        <span class="text-muted ms-2">
                            — {{ \Carbon\Carbon::parse($day->date)->format('d M Y') }}
                        </span>
                    @endif

                    @if($day->city)
                        <span class="badge bg-light text-dark border ms-2">
                            <i class="fas fa-city me-1"></i> {{ $day->city }}
                        </span>
                    @endif
                </div>

                <span class="text-muted small">
                    {{ $day->items->count() }} kegiatan
                </span>
            </div>

            {{-- ITEMS --}}
            <div class="card-body p-0">

                @if($day->items->isEmpty())
                    <div class="p-3 text-muted text-center">
                        Belum ada kegiatan di hari ini.
                    </div>
                @else

                    <div class="list-group list-group-flush">

                        @foreach($day->items as $item)
                            <div class="list-group-item py-3 border-0 border-bottom">

                                <div class="d-flex align-items-start">

                                    {{-- TIME BUBBLE --}}
                                    <div class="me-4" style="width:80px">
                                        <div class="px-3 py-2 rounded-pill bg-primary text-white shadow-sm text-center fw-semibold">
                                            {{ $item->time ? \Carbon\Carbon::parse($item->time)->format('H:i') : '--:--' }}
                                        </div>
                                    </div>

                                    {{-- CONTENT --}}
                                    <div class="flex-fill">
                                        @if($item->title)
                                            <div class="fw-semibold fs-6 mb-1">{{ $item->title }}</div>
                                        @endif

                                        <div class="text-muted small">
                                            {!! nl2br(e($item->content)) !!}
                                        </div>
                                    </div>

                                    {{-- SEQUENCE --}}
                                    <div class="ms-3">
                                        <span class="badge bg-light text-muted border">
                                            #{{ $item->sequence }}
                                        </span>
                                    </div>

                                </div>

                            </div>
                        @endforeach

                    </div>

                @endif
            </div>

        </div>
    @empty
        <div class="text-center text-muted py-5">
            Belum ada hari pada itinerary ini. <br>
            <a class="fw-semibold" href="{{ route('admin.itinerary.fill-days', $itinerary) }}">
                Tambahkan kegiatan sekarang
            </a>.
        </div>
    @endforelse
</div>
@endsection
