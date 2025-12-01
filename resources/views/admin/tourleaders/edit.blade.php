@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 700px; margin: 50px auto;">
    <div class="card shadow-lg border-0" style="border-radius: 15px; overflow: hidden;">
        <!-- Header -->
        <div class="card-header text-white" style="background: linear-gradient(135deg, #1f3c88, #162c5c);">
            <h3 class="mb-0">
                <i class="bi bi-pencil-square me-2 text-warning"></i>
                Edit Tour Leader
            </h3>
        </div>

        <!-- Body -->
        <div class="card-body" style="background-color: #f9f9f9;">
            <form method="POST" action="{{ route('tourleaders.update', $tourleader->id) }}">
                @csrf
                @method('PUT')

                <!-- Nama -->
                <div class="mb-3">
                    <label class="form-label fw-bold">
                        <i class="bi bi-person-fill me-1 text-primary"></i> Nama
                    </label>
                    <input type="text" name="name" class="form-control" 
                        value="{{ $tourleader->name }}" placeholder="Masukkan nama lengkap" required>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label class="form-label fw-bold">
                        <i class="bi bi-envelope-fill me-1 text-success"></i> Email
                    </label>
                    <input type="email" name="email" class="form-control" 
                           value="{{ $tourleader->email }}" placeholder="Masukkan email" required>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label class="form-label fw-bold">
                        <i class="bi bi-lock-fill me-1 text-danger"></i> Password
                    </label>
                    <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak diubah">
                </div>

                <!-- Kloter -->
<div class="mb-4">
    <label class="form-label fw-bold">
        <i class="bi bi-people-fill me-1 text-warning"></i> Kloter
    </label>
    <select name="kloter_id" class="form-select" required>
        <option value="">-- Pilih Kloter --</option>
        @foreach($kloters as $kloter)
            <option value="{{ $kloter->id }}" 
                {{ $tourleader->kloter_id == $kloter->id ? 'selected' : '' }}>
                {{ $kloter->nama }} ({{ $kloter->tanggal }})
            </option>
        @endforeach
    </select>
</div>


                <!-- Tombol -->
                <button type="submit" class="btn w-100 text-white fw-bold"
                    style="background: linear-gradient(135deg, #1f3c88, #162c5c); border-radius: 8px; transition: 0.3s;">
                    <i class="bi bi-arrow-repeat me-2"></i> Update
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
@endsection
