@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Riwayat Scan Koper</h1>

    <!-- Filter Form -->
    <form method="GET" action="{{ route('scans.index') }}" class="row g-3 mb-4">
        <div class="col-md-4">
            <label class="form-label">Tour Leader</label>
            <select name="tourleader" class="form-select">
                <option value="">-- Semua Tour Leader --</option>
                @foreach($tourleaders as $leader)
                    <option value="{{ $leader->id }}" {{ request('tourleader') == $leader->id ? 'selected' : '' }}>
                        {{ $leader->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label">Tanggal Scan</label>
            <input type="date" name="date" class="form-control" value="{{ request('date') }}">
        </div>

        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-primary me-2">Filter</button>
            <a href="{{ route('scans.index') }}" class="btn btn-secondary me-2">Reset</a>

            <!-- Export Excel ikut parameter filter -->
            <a href="{{ route('scans.export', [
                    'tourleader' => request('tourleader'),
                    'date' => request('date')
                ]) }}" class="btn btn-success">
                ⬇️ Export Excel
            </a>
        </div>
    </form>

    <!-- Tabel Data dengan Scroll -->
    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
        <table class="table table-bordered align-middle text-center">
            <thead class="table-dark sticky-top">
                <tr>
                    <th>No</th>
                    <th>Kode Koper</th>
                    <th>Nama Pemilik</th>
                    <th>Nomor Telepon</th>
                    <th>Tour Leader</th>
                    <th>Kloter</th>
                    <th>Status Scan</th>
                </tr>
            </thead>
            <tbody>
                @php
                    // Urutkan data berdasarkan kode koper ASCENDING (contoh: 001, 002, 010, dst)
                    $sortedScans = $scans->sortBy(function($scan) {
                        // hilangkan leading zeros, tapi tetap jaga urutan numerik
                        return (int) ltrim($scan->koper_code, '0');
                    });
                @endphp

                @forelse($sortedScans as $scan)
                <tr class="{{ $scan->id ? 'table-success' : 'table-danger' }}">
                    <td>{{ $loop->iteration }}</td> {{-- Nomor urut dinamis --}}
                    <td><strong>{{ $scan->koper_code }}</strong></td>

                    {{-- Nama Pemilik --}}
                    <td>
                        {{ $scan->owner_name
                            ?? data_get($scan, 'owner.name')
                            ?? data_get($scan, 'koper.owner_name')
                            ?? '-' }}
                    </td>

                    {{-- Nomor Telepon --}}
                    <td>
                        {{ $scan->owner_phone
                            ?? $scan->phone
                            ?? $scan->phone_number
                            ?? data_get($scan, 'owner.phone')
                            ?? data_get($scan, 'koper.owner_phone')
                            ?? '-' }}
                    </td>

                    <td>{{ optional($scan->tourleader)->name ?? '-' }}</td>
                    <td>{{ $scan->kloter ?? '-' }}</td>

                    <td>
                        @if(!empty($scan->scanned_at))
                            ✅ {{ $scan->scanned_at }}
                        @else
                            ❌ Belum discan
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">Belum ada data scan koper</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
