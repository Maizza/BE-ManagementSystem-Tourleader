@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-2 fw-bold">Dashboard Admin</h1>
    <p class="text-muted mb-4">Atur semua data dengan mudah</p>
    <!-- Statistik -->
    <div class="row g-4">
        <!-- Total Scan -->
        <div class="col-md-4">
            <div class="card stat-card border-0 shadow-lg text-center">
                <div class="card-body">
                    <i class="bi bi-arrow-clockwise display-4 icon-yellow mb-2"></i>
                    <h5 class="card-title fw-bold">Total Scan</h5>
                    <p class="card-text fs-3 fw-semibold">{{ $totalScans }}</p>
                </div>
            </div>
        </div>

        <!-- Total Tour Leader -->
        <div class="col-md-4">
            <div class="card stat-card border-0 shadow-lg text-center">
                <div class="card-body">
                    <i class="bi bi-people-fill display-4 icon-yellow mb-2"></i>
                    <h5 class="card-title fw-bold">Total Tour Leader</h5>
                    <p class="card-text fs-3 fw-semibold">{{ $totalTourLeaders }}</p>
                </div>
            </div>
        </div>

        <!-- Total Notifikasi -->
        <div class="col-md-4">
            <div class="card stat-card border-0 shadow-lg text-center">
                <div class="card-body">
                    <i class="bi bi-bell-fill display-4 icon-yellow mb-2"></i>
                    <h5 class="card-title fw-bold">Total Notifikasi</h5>
                    <p class="card-text fs-3 fw-semibold">{{ $totalNotifikasi ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom CSS -->
<style>
    .stat-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 15px;
        background: linear-gradient(145deg, #1f3c88, #162c5c);
        color: #fff;
    }
    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.3);
    }
    .stat-card h5,
    .stat-card p {
        color: #fff !important;
    }
    .icon-yellow {
        color: #f1c40f !important;
        animation: bounce 1.5s infinite;
    }
    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-6px); }
    }
</style>
@endsection
