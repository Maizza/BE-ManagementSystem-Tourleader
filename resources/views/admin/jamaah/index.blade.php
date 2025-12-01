{{-- resources/views/admin/jamaah/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Data Jamaah')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Data Jamaah</h1>

        <div>
            <a href="{{ route('jamaah.importForm') }}" class="btn btn-primary">
                Import dari Excel
            </a>
        </div>
    </div>

    {{-- Alert sukses / error --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Tabel Data Jamaah --}}
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 60px;">#</th>
                            <th>Nama Jamaah</th>
                            <th>No. Paspor</th>
                            <th>No. HP</th>
                            <th>JK</th>
                            <th>Tgl Lahir</th>
                            <th>Kloter</th>
                            <th>No. Bus</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($jamaah as $index => $j)
                            <tr>
                                <td>{{ $jamaah->firstItem() + $index }}</td>
                                <td>{{ $j->nama_jamaah }}</td>
                                <td>{{ $j->no_paspor ?? '-' }}</td>
                                <td>{{ $j->no_hp ?? '-' }}</td>
                                <td>{{ $j->jenis_kelamin ?? '-' }}</td>
                                <td>{{ $j->tanggal_lahir ? \Carbon\Carbon::parse($j->tanggal_lahir)->format('d-m-Y') : '-' }}</td>
                                <td>{{ $j->kode_kloter ?? '-' }}</td>
                                <td>{{ $j->nomor_bus ?? '-' }}</td>
                                <td>{{ $j->keterangan ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    Belum ada data jamaah. Silakan import dari Excel.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($jamaah instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="card-footer">
                {{ $jamaah->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
