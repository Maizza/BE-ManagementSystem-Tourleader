{{-- resources/views/admin/attendances/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container py-4">
  <h1 class="h4 mb-3">Riwayat Absensi Tour Leader</h1>

  <div class="table-responsive">
    <table class="table table-bordered align-middle">
      <thead class="table-light">
        <tr>
          <th>Tanggal</th>
          <th>Nama</th>
          <th>Kloter</th>
          <th>Foto</th>
          <th>Koordinat</th>
          <th>Map</th>
        </tr>
      </thead>
      <tbody>
        @forelse($rows as $r)
          <tr>
            <td>{{ \Carbon\Carbon::parse($r->created_at)->format('Y-m-d H:i') }}</td>

            {{-- nama TL dari join --}}
            <td>{{ $r->tl_name ?? $r->name ?? '-' }}</td>

            {{-- kloter: pakai nama & tanggal dari join; fallback ke kolom lama jika ada --}}
            <td>
              @php
                $label = $r->kloter_nama;
                // fallback jika dulu pernah simpan slug/string di attendances.kloter
                if (!$label && !empty($r->kloter)) {
                    if ($r->kloter === 'umrah_reguler_cemerlang')  $label = 'Umrah Reguler Cemerlang';
                    elseif ($r->kloter === 'umrah_reguler_super_cermat') $label = 'Umrah Reguler Super Cermat';
                    else $label = $r->kloter; // tampilkan apa adanya
                }
              @endphp

              {{ $label ?? '-' }}
              @if(!empty($r->kloter_tanggal))
                <div class="small text-muted">{{ $r->kloter_tanggal }}</div>
              @endif
            </td>

            <td>
              @if(!empty($r->photo_path))
                <a href="{{ asset('storage/'.$r->photo_path) }}" target="_blank">
                  <img src="{{ asset('storage/'.$r->photo_path) }}" alt="foto" style="height:56px;border-radius:6px;">
                </a>
              @else
                -
              @endif
            </td>

            <td>
              @if(!empty($r->lat) && !empty($r->lng))
                {{ $r->lat }}, {{ $r->lng }}
              @else
                -
              @endif
            </td>

            <td>
              @if(!empty($r->lat) && !empty($r->lng))
                <a class="btn btn-sm btn-outline-primary"
                   target="_blank"
                   href="https://www.google.com/maps?q={{ $r->lat }},{{ $r->lng }}">
                   Lihat Maps
                </a>
              @endif
            </td>
          </tr>
        @empty
          <tr><td colspan="6" class="text-center text-muted">Belum ada data.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{ $rows->links() }}
</div>
@endsection
