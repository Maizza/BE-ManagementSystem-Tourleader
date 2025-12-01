@extends('layouts.app')

@section('content')
<div class="container py-4">
  <div class="d-flex align-items-start justify-content-between mb-3">
    <div>
      <h1 class="h4 mb-1">Hasil Tugas: {{ $task->title }}</h1>
      <div class="text-muted small">
        Periode: {{ $task->opens_at->format('d M Y H:i') }} – {{ $task->closes_at->format('d M Y H:i') }}
      </div>
    </div>
    <a href="{{ route('admin.tasks.index') }}" class="btn btn-outline-secondary">Kembali</a>
  </div>

  <div class="row g-3">
    {{-- Belum mengerjakan --}}
    <div class="col-md-6">
      <div class="card h-100">
        <div class="card-header"><strong>Belum mengerjakan</strong></div>
        <div class="table-responsive">
          <table class="table mb-0">
            <thead class="table-light">
              <tr>
                <th style="width:64px">No</th>
                <th>Nama Tour Leader</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($notDone as $i => $tl)
                <tr>
                  <td>{{ $i+1 }}</td>
                  <td>{{ $tl->name }}</td>
                  <td><span class="badge bg-danger">Belum dikerjakan</span></td>
                </tr>
              @empty
                <tr><td colspan="3" class="text-center text-muted">Semua sudah mengerjakan</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

    {{-- Sudah mengerjakan --}}
    <div class="col-md-6">
      <div class="card h-100">
        <div class="card-header"><strong>Sudah mengerjakan</strong></div>
        <div class="table-responsive">
          <table class="table mb-0">
            <thead class="table-light">
              <tr>
                <th style="width:64px">No</th>
                <th>Nama Tour Leader</th>
                <th>Status</th> {{-- <== ganti dari Waktu ke Status --}}
              </tr>
            </thead>
            <tbody>
              @forelse ($done as $i => $tl)
                <tr>
                  <td>{{ $i+1 }}</td>
                  <td>{{ $tl->name }}</td>
                  <td>
                    <span class="badge bg-success">Sudah dikerjakan</span>
                    {{-- Jika tetap ingin menampilkan waktu kecil sebagai info tambahan, buka komentar di bawah: --}}
                    {{-- <div class="small text-muted mt-1">{{ optional($tl->pivot->done_at)->format('d M Y H:i') }}</div> --}}
                  </td>
                </tr>
              @empty
                <tr><td colspan="3" class="text-center text-muted">Belum ada yang mengerjakan</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection