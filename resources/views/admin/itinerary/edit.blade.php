@extends('layouts.app')

@section('content')
<div class="container" style="max-width:1000px">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Edit Itinerary & Semua Harinya</h3>
        <a href="{{ route('admin.itinerary.index') }}" class="btn btn-light">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    {{-- SUCCESS --}}
    @if(session('ok'))
        <div class="alert alert-success">{{ session('ok') }}</div>
    @endif

    {{-- ERROR --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- =============================
        FORM EDIT ITINERARY
    ============================== --}}
    <form action="{{ route('admin.itinerary.update', $itinerary) }}"
          method="POST" class="card p-3 mb-4">
        @csrf
        @method('PUT')

        <h5 class="mb-3">Informasi Utama Itinerary</h5>

        <div class="mb-3">
            <label class="form-label">Judul</label>
            <input type="text" class="form-control" name="title" required
                   value="{{ old('title', $itinerary->title) }}">
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Tanggal Mulai</label>
                <input type="date" name="start_date" class="form-control"
                    value="{{ old('start_date', optional($itinerary->start_date)->toDateString()) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Tanggal Selesai</label>
                <input type="date" name="end_date" class="form-control"
                    value="{{ old('end_date', optional($itinerary->end_date)->toDateString()) }}">
            </div>
        </div>

        <label class="form-label">Tour Leader</label>
<div class="border rounded p-3 bg-light">

    @if($itinerary->tourLeaders->count() > 0)
        @foreach($itinerary->tourLeaders as $tl)
            <span class="badge bg-primary me-1 mb-1" style="font-size:14px;">
                {{ $tl->name }}
            </span>
        @endforeach
    @else
        <p class="text-muted mb-0 fst-italic">Tidak ada tour leader terdaftar.</p>
    @endif

</div>



        <button class="btn btn-primary w-100">
            <i class="fas fa-save"></i> Simpan Perubahan Itinerary
        </button>
    </form>


    {{-- =============================
        SECTION DAY LIST
    ============================== --}}
    <h4 class="mb-3">Edit Days & Kegiatan</h4>

    @foreach($itinerary->days as $day)
        @php
            $readDate = $day->date ? \Carbon\Carbon::parse($day->date)->format('Y-m-d') : '';
        @endphp

        <div class="card mb-4">
            <div class="card-header bg-light">
                <strong>Day {{ $day->day_number }}</strong>
            </div>

            <div class="card-body">

                {{-- FORM UPDATE DAY --}}
                <form action="{{ route('admin.day.update', $day) }}" method="POST" class="mb-3">
                    @csrf @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Kota</label>
                            <input type="text" name="city" class="form-control"
                                   value="{{ old('city', $day->city) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="date" class="form-control"
                                   value="{{ $readDate }}">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button class="btn btn-primary w-100">Update Day</button>
                        </div>
                    </div>
                </form>

                <hr>

                {{-- LIST ITEMS --}}
                <h6>Kegiatan</h6>

                @forelse($day->items as $item)
                    <div class="border rounded p-3 mb-2">

                        {{-- FORM UPDATE ITEM --}}
                        <form action="{{ route('admin.day.item.update', $item) }}" method="POST">
                            @csrf @method('PUT')

                            <div class="row g-3">
                                <div class="col-md-2">
                                    <label class="form-label">Jam</label>
                                    <input type="time" name="time" class="form-control"
                                           value="{{ substr($item->time,0,5) }}">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Judul</label>
                                    <input type="text" name="title" class="form-control"
                                           value="{{ $item->title }}">
                                </div>

                                <div class="col-md-5">
                                    <label class="form-label">Isi</label>
                                    <textarea name="content" class="form-control" rows="2">{{ $item->content }}</textarea>
                                </div>

                                <div class="col-md-2 d-flex align-items-end gap-1">
                                    <button class="btn btn-success w-100 btn-sm">Simpan</button>
                                </div>
                            </div>
                        </form>

                        {{-- FORM DELETE ITEM --}}
                        <form action="{{ route('admin.day.item.destroy', $item) }}"
                              method="POST" class="mt-1"
                              onsubmit="return confirm('Hapus kegiatan ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-outline-danger btn-sm w-100">Hapus</button>
                        </form>

                    </div>
                @empty
                    <p class="text-muted fst-italic">Belum ada kegiatan.</p>
                @endforelse
                
            </div>
        </div>

    @endforeach


    {{-- =============================
        DELETE ITINERARY
    ============================== --}}
    <form action="{{ route('admin.itinerary.destroy', $itinerary) }}"
          method="POST"
          onsubmit="return confirm('Hapus itinerary beserta semua harinya?')">
        @csrf @method('DELETE')

        <button class="btn btn-outline-danger w-100">
            <i class="fas fa-trash"></i> Hapus Seluruh Itinerary
        </button>
    </form>

</div>
@endsection
