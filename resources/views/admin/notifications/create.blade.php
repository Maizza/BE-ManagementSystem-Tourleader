@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 750px; margin: 50px auto;">
    <div class="card shadow-lg border-0" style="border-radius: 15px; overflow: hidden;">
        <!-- Header -->
        <div class="card-header text-center text-white" style="background: linear-gradient(135deg, #1f3c88, #162c5c);">
            <h3 class="mb-0">
                <i class="bi bi-bell-fill me-2 text-warning"></i>
                Buat Notifikasi
            </h3>
        </div>

        <!-- Body -->
        <div class="card-body" style="background-color: #f9f9f9;">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('admin.notifications.send') }}" method="POST">
                @csrf

                <!-- Judul -->
                <div class="mb-3">
                    <label class="form-label fw-bold">
                        <i class="bi bi-type me-1 text-primary"></i> Judul Notifikasi
                    </label>
                    <input type="text" name="title" class="form-control" placeholder="Masukkan judul..." required>
                </div>

                <!-- Isi -->
                <div class="mb-3">
                    <label class="form-label fw-bold">
                        <i class="bi bi-chat-left-text me-1 text-success"></i> Isi Notifikasi
                    </label>
                    <textarea name="message" class="form-control" rows="4" placeholder="Tulis isi notifikasi..." required></textarea>
                </div>

                <!-- Token -->
                <div class="mb-4">
                    <label class="form-label fw-bold">
                        <i class="bi bi-key me-1 text-warning"></i> FCM Token (opsional)
                    </label>
                    <input type="text" name="fcm_token" class="form-control" placeholder="Masukkan token jika ingin kirim ke device tertentu">
                </div>

                <!-- Tombol -->
                <button type="submit" class="btn w-100 text-white fw-bold"
                    style="background: linear-gradient(135deg, #1f3c88, #162c5c); border-radius: 8px; transition: 0.3s;">
                    <i class="bi bi-send-fill me-2"></i> Kirim Notifikasi
                </button>
            </form>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
@endsection
