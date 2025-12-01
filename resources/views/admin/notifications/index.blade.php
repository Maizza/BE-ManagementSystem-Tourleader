@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-dark fw-bold">Daftar Notifikasi</h1>
        <a href="{{ route('admin.notifications.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Buat Notifikasi Baru
        </a>
    </div>
    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>No</th> {{-- Tambahkan kolom nomor urut --}}
                        <th>Judul</th>
                        <th>Pesan</th>
                        <th>Aktif</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($notifications as $notif)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $notif->title }}</td>
                            <td>{{ $notif->message }}</td>
                            <td>
                                @if($notif->is_active)
                                    <span class="badge bg-success">Ya</span>
                                @else
                                    <span class="badge bg-danger">Tidak</span>
                                @endif
                            </td>
                            <td>{{ $notif->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Belum ada notifikasi</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
