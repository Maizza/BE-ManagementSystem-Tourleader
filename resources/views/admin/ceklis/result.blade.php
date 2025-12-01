@extends('layouts.app')

@section('content')
<div class="container">
  <h5 class="mb-1">Detail hasil — {{ $task->title }}</h5>
  <div class="small text-muted mb-3">
    Tanggal: {{ $task->opens_at->format('d M Y H:i') }} — {{ $task->closes_at->format('d M Y H:i') }}
  </div>

  <div class="row g-4">
    <!-- Belum Mengerjakan -->
    <div class="col-12">
      <div class="card h-100">
        <div class="card-header fw-semibold">Belum mengerjakan</div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-sm table-bordered mb-0 align-middle">
              <thead class="table-dark">
                <tr>
                  <th style="width:60px">NO</th>
                  <th>Nama Tour leader</th>
                  <th style="width:160px">Status</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($belum as $i => $tl)
                  <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $tl->name }}</td>
                    <td><span class="badge bg-warning text-dark">Belum dikerjakan</span></td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="3" class="text-center text-muted">Semua sudah mengerjakan</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Sudah Mengerjakan -->
    <div class="col-12">
      <div class="card h-100">
        <div class="card-header fw-semibold">Sudah mengerjakan</div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-sm table-bordered mb-0 align-middle">
              <thead class="table-dark">
                <tr>
                  <th style="width:60px">NO</th>
                  <th>Nama Tourleader</th>
                  <th>Nama Petugas</th>
                  <th>Kloter</th>
                  <th style="width:160px">Status</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($sudah as $i => $sub)
                  <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $sub->tourleader->name ?? '-' }}</td>
                    <td>{{ $sub->nama_petugas }}</td>
                    <td>{{ $sub->kloter }}</td>
                    <td><span class="badge bg-success">Sudah dikerjakan</span></td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5" class="text-center text-muted">Belum ada submission</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection
