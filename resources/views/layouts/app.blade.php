<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Retail System') }}</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">


    <style>
        :root {
            --sidebar-width: 280px;
            --primary-color: #1A365D; /* Navy blue - elegan dan profesional */
            --secondary-color: #2C5282; /* Blue lebih terang */
            --accent-color: #D4AF37; /* Emas - untuk aksen */
            --light-bg: #F7FAFC; /* Light blue gray */
            --dark-bg: #1E1E2D;
            --text-light: #FFFFFF;
            --text-dark: #2D3748;
            --border-color: #E2E8F0;
            --success-color: #38A169;
            --warning-color: #D69E2E;
            --danger-color: #E53E3E;
            --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            display: flex;
            background: var(--light-bg);
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--primary-color) 0%, #2A4365 100%);
            color: var(--text-light);
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            box-shadow: var(--card-shadow);
            z-index: 1000;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            transition: var(--transition);
        }

        .sidebar-header {
            padding: 28px 24px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
            background: rgba(0, 0, 0, 0.1);
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 14px;
            text-decoration: none;
            color: var(--text-light);
            font-weight: 700;
            font-size: 1.4rem;
        }

        .sidebar-brand img {
            height: 42px;
            width: auto;
            border-radius: 8px;
        }

        .nav-pills {
            padding: 24px 16px;
            flex-grow: 1;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.85);
            border-radius: 12px;
            padding: 14px 20px;
            margin-bottom: 8px;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 16px;
            font-weight: 500;
            font-size: 0.95rem;
            position: relative;
            overflow: hidden;
            border: none;
            background: transparent;
            width: 100%;
            text-align: left;
        }

        .sidebar .nav-link i {
            font-size: 1.2rem;
            width: 24px;
            text-align: center;
            transition: var(--transition);
            color: var(--accent-color); /* Ikon berwarna emas */
        }

        .sidebar .nav-link:hover {
            background: rgba(255, 255, 255, 0.15);
            color: var(--text-light);
            transform: translateX(5px);
        }

        .sidebar .nav-link:hover i {
            transform: scale(1.1);
            color: #FFD700; /* Warna emas lebih terang saat hover */
        }

        .sidebar .nav-link.active {
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--primary-color) 100%);
            color: var(--text-light);
            font-weight: 600;
            box-shadow: 0 6px 20px rgba(42, 67, 101, 0.4);
            border-left: 4px solid var(--accent-color); /* Garis aksen emas */
        }

        .sidebar .nav-link.active::before {
            display: none; /* Menghilangkan garis sebelumnya */
        }

        .sidebar .nav-link.active i {
            color: var(--accent-color); /* Ikon berwarna emas untuk menu aktif */
        }

        /* Submenu Styles */
        .submenu {
            padding-left: 30px;
            margin-top: 4px;
            overflow: hidden;
            transition: var(--transition);
        }

        .submenu-item {
            padding: 12px 20px;
            margin-bottom: 4px;
            border-radius: 10px;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 12px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-size: 0.9rem;
        }

        .submenu-item:hover {
            background: rgba(255, 255, 255, 0.1);
            color: var(--text-light);
            transform: translateX(3px);
        }

        .submenu-item.active {
            background: rgba(255, 255, 255, 0.2);
            color: var(--text-light);
            font-weight: 500;
            border-left: 3px solid var(--accent-color); /* Garis aksen emas */
        }

        .submenu-item i {
            font-size: 1rem;
            width: 20px;
            color: var(--accent-color); /* Ikon submenu berwarna emas */
        }

        .sidebar-footer {
            padding: 24px 20px;
            margin-top: auto;
            border-top: 1px solid rgba(255, 255, 255, 0.12);
            background: rgba(0, 0, 0, 0.1);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 20px;
        }

        .user-avatar {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent-color) 0%, #B8860B 100%); /* Gradien emas */
            color: var(--primary-color); /* Warna teks navy */
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .user-details {
            flex-grow: 1;
        }

        .user-name {
            font-weight: 600;
            font-size: 0.95rem;
            margin-bottom: 2px;
        }

        .user-role {
            font-size: 0.8rem;
            opacity: 0.85;
            color: var(--accent-color); /* Warna emas untuk role */
        }

        .btn-logout {
            background: rgba(255, 255, 255, 0.12);
            color: var(--text-light);
            border: none;
            border-radius: 10px;
            padding: 12px 20px;
            width: 100%;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-weight: 500;
            font-size: 0.9rem;
            cursor: pointer;
        }

        .btn-logout:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-1px);
            color: var(--accent-color); /* Warna emas saat hover */
        }

        /* ===== CONTENT ===== */
        .content {
            flex-grow: 1;
            padding: 30px 40px;
            margin-left: var(--sidebar-width);
            transition: var(--transition);
            min-height: 100vh;
        }

        .content-header {
            margin-bottom: 32px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border-color);
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color); /* Warna navy untuk judul */
            margin-bottom: 8px;
        }

        .breadcrumb {
            margin-bottom: 0;
            font-size: 0.9rem;
            color: #718096;
        }

        .breadcrumb-item.active {
            color: var(--primary-color);
            font-weight: 500;
        }

        /* ===== CARDS ===== */
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            margin-bottom: 24px;
            transition: var(--transition);
            background: var(--text-light);
        }

        .card:hover {
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
            transform: translateY(-2px);
        }

        .card-header {
            background: var(--text-light);
            border-bottom: 1px solid var(--border-color);
            font-weight: 600;
            padding: 20px 24px;
            border-radius: 16px 16px 0 0 !important;
            color: var(--primary-color); /* Warna navy untuk header card */
            font-size: 1.1rem;
        }

        .card-body {
            padding: 24px;
        }

        /* ===== STATS CARDS ===== */
        .stats-card {
            border-radius: 16px;
            padding: 24px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: var(--text-light);
            height: 100%;
            transition: var(--transition);
            border-bottom: 3px solid var(--accent-color); /* Garis aksen emas */
        }

        .stats-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 35px rgba(26, 54, 93, 0.25);
        }

        .stats-icon {
            font-size: 2.5rem;
            margin-bottom: 16px;
            opacity: 0.9;
            color: var(--accent-color); /* Ikon stats berwarna emas */
        }

        .stats-number {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .stats-label {
            font-size: 0.9rem;
            opacity: 0.9;
            font-weight: 500;
        }

        /* ===== BUTTONS ===== */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            border-radius: 10px;
            padding: 12px 24px;
            font-weight: 600;
            transition: var(--transition);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(26, 54, 93, 0.3);
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--primary-color) 100%);
        }

        /* ===== TABLES ===== */
        .table {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
        }

        .table thead th {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: var(--text-light);
            border: none;
            padding: 16px;
            font-weight: 600;
        }

        .table tbody td {
            padding: 16px;
            vertical-align: middle;
            border-color: var(--border-color);
        }

        /* Warna aksen untuk elemen tabel */
        .table-hover tbody tr:hover {
            background-color: rgba(212, 175, 55, 0.1); /* Warna emas transparan saat hover */
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1200px) {
            :root {
                --sidebar-width: 240px;
            }

            .content {
                padding: 25px 30px;
            }
        }

        @media (max-width: 992px) {
            .sidebar {
                width: 80px;
                transform: translateX(0);
            }

            .sidebar-brand span,
            .nav-link span,
            .user-details,
            .btn-logout span,
            .submenu {
                display: none;
            }

            .sidebar-brand,
            .nav-link,
            .btn-logout {
                justify-content: center;
                padding: 15px 12px;
            }

            .nav-pills {
                padding: 20px 8px;
            }

            .content {
                margin-left: 80px;
                padding: 20px;
            }

            .sidebar:hover {
                width: var(--sidebar-width);
            }

            .sidebar:hover .sidebar-brand span,
            .sidebar:hover .nav-link span,
            .sidebar:hover .user-details,
            .sidebar:hover .btn-logout span,
            .sidebar:hover .submenu {
                display: block;
            }

            .sidebar:hover .sidebar-brand,
            .sidebar:hover .nav-link,
            .sidebar:hover .btn-logout {
                justify-content: flex-start;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: fixed;
                bottom: 0;
                top: auto;
                height: 70px;
                z-index: 1000;
            }

            .sidebar-header,
            .user-info,
            .sidebar-footer,
            .submenu {
                display: none;
            }

            .nav-pills {
                display: flex;
                justify-content: space-around;
                padding: 10px;
                gap: 5px;
            }

            .nav-item {
                flex: 1;
            }

            .nav-link {
                flex-direction: column;
                padding: 10px 5px;
                font-size: 0.7rem;
                text-align: center;
                margin-bottom: 0;
                border-radius: 10px;
            }

            .nav-link i {
                font-size: 1.1rem;
                margin-bottom: 4px;
                color: var(--accent-color); /* Pastikan ikon tetap berwarna emas */
            }

            .nav-link span {
                display: block !important;
                font-size: 0.65rem;
            }

            .content {
                margin-left: 0;
                margin-bottom: 70px;
                padding: 20px 15px;
            }

            .page-title {
                font-size: 1.6rem;
            }
        }

        @media (max-width: 480px) {
            .content {
                padding: 15px 12px;
            }

            .card-body {
                padding: 18px;
            }

            .page-title {
                font-size: 1.4rem;
            }
        }

        /* ===== ANIMATIONS ===== */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        /* ===== SCROLLBAR ===== */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        /* ===== UTILITIES ===== */
        .text-primary {
            color: var(--primary-color) !important;
        }

        .bg-primary {
            background: var(--primary-color) !important;
        }

        .border-primary {
            border-color: var(--primary-color) !important;
        }

        .text-accent {
            color: var(--accent-color) !important;
        }

        .bg-accent {
            background: var(--accent-color) !important;
        }
    </style>
</head>

<body>
   <!-- Sidebar -->
<div class="sidebar">
    <div class="sidebar-header">
        <a href="{{ route('dashboard') }}" class="sidebar-brand">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="sidebar-logo">
            <span>Retali System</span>
        </a>
    </div>

    <ul class="nav nav-pills flex-column mb-auto">

        <!-- Dashboard -->
        <li class="nav-item">
            <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}"
               href="{{ route('dashboard') }}">
                <i class="fas fa-chart-line"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <!-- Pengguna -->
        <li class="nav-item">
            <a class="nav-link {{ request()->is('pengguna*', 'tourleaders*') ? 'active' : '' }}"
            href="#penggunaSubmenu" data-bs-toggle="collapse"
            aria-expanded="{{ request()->is('pengguna*', 'tourleaders*') ? 'true' : 'false' }}">
                <i class="fas fa-users"></i>
                <span>Pengguna</span>
                <i class="fas fa-chevron-down ms-auto"></i>
            </a>
            <div class="collapse {{ request()->is('pengguna*', 'tourleaders*') ? 'show' : '' }}"
                 id="penggunaSubmenu">
                <div class="submenu">
                    <a href="{{ route('tourleaders.index') }}"
                       class="submenu-item {{ request()->is('tourleaders*') ? 'active' : '' }}">
                        <i class="fas fa-user-tie"></i>
                        Tour Leader
                    </a>
                    <a href=""
                       class="submenu-item {{ request()->is('pengguna/mutowif*') ? 'active' : '' }}">
                        <i class="fas fa-user-check"></i>
                        Muthawif
                    </a>
                </div>
            </div>
        </li>

        <!-- Tugas -->
        <li class="nav-item">
            <a class="nav-link {{ request()->is('tugas*') ? 'active' : '' }}"
            href="#tugasSubmenu" data-bs-toggle="collapse"
            aria-expanded="{{ request()->is('tugas*') ? 'true' : 'false' }}">
                <i class="fas fa-tasks"></i>
                <span>Tugas</span>
                <i class="fas fa-chevron-down ms-auto"></i>
            </a>
            <div class="collapse {{ request()->is('tugas*') ? 'show' : '' }}" id="tugasSubmenu">
                <div class="submenu">
                    <a href="{{ route('admin.tasks.index') }}"
                       class="submenu-item {{ request()->is('admin/tugas*') ? 'active' : '' }}">
                        <i class="fas fa-user-cog"></i>
                        Tugas Tourleader
                    </a>
                    <a href="{{ route('admin.ceklis.index') }}"
                       class="submenu-item {{ request()->is('tugas/ceklis*') ? 'active' : '' }}">
                        <i class="fas fa-clipboard-check"></i>
                        Tugas Ceklis
                    </a>
                </div>
            </div>
        </li>

        <!-- Itinerary (NEW) -->
        <!-- Itinerary -->

        <li class="nav-item">
    <a class="nav-link {{ request()->routeIs('admin.itineraries.*') ? 'active' : '' }}"
     href="#itinerarySubmenu"
     data-bs-toggle="collapse"
     aria-expanded="{{ request()->routeIs('admin.itineraries.*') ? 'true' : 'false' }}">
    <i class="fas fa-map-marked-alt"></i>
    <span>Itinerary</span>
    <i class="fas fa-chevron-down ms-auto"></i>
  </a>

  <div class="collapse {{ request()->routeIs('admin.itinerary.*') ? 'show' : '' }}" id="itinerarySubmenu">
    <div class="submenu">
      <a href="{{ route('admin.itinerary.index') }}"
         class="submenu-item {{ request()->routeIs('admin.itinerary.index') ? 'active' : '' }}">
        <i class="fas fa-map"></i> Halaman Itinerary
      </a>

       <!-- Pilihan Kota (contoh; ganti ke route yg benar kalau sudah ada) -->
            <a href="{{ route('admin.itinerary.kota.index') }}"
            class="submenu-item {{ request()->routeIs('admin.itinerary.kota.index') ? 'active' : '' }}">                <i class="fas fa-city"></i>
                Pilihan Kota
            </a>
    </div>
  </div>
</li>

        <!-- Kloter -->
        <li class="nav-item">
            <a class="nav-link {{ request()->is('kloter*') ? 'active' : '' }}"
               href="{{ route('kloter.index') }}">
                <i class="fas fa-plane-departure"></i>
                <span>Kloter</span>
            </a>
        </li>

        <!-- Riwayat Absensi -->
<li class="nav-item">
    <a class="nav-link {{ request()->is('admin/attendances*', 'admin/absensi*') ? 'active' : '' }}"
       href="#absensiSubmenu"
       data-bs-toggle="collapse"
       aria-expanded="{{ request()->is('admin/attendances*', 'admin/absensi*') ? 'true' : 'false' }}">
        <i class="fas fa-user-clock"></i>
        <span>Riwayat Absensi</span>
        <i class="fas fa-chevron-down ms-auto"></i>
    </a>

    <div class="collapse {{ request()->is('admin/attendances*', 'admin/absensi*') ? 'show' : '' }}" id="absensiSubmenu">
        <div class="submenu">

            <!-- Absensi Tour Leader -->
            <a href="{{ route('admin.attendances.index') }}"
               class="submenu-item {{ request()->is('admin/attendances*') ? 'active' : '' }}">
                <i class="fas fa-user-tie"></i>
                Absensi Tour Leader
            </a>

            <!-- Absensi Tour Leader -->
            <a href="{{ route('admin.jamaah.index') }}"
               class="submenu-item {{ request()->is('admin/jamaah*') ? 'active' : '' }}">
                <i class="fas fa-user-tie"></i>
                Absensi Jamaah
            </a>

        </div>
    </div>
</li>


        <!-- Riwayat Scan -->
        <li class="nav-item">
            <a class="nav-link {{ request()->is('scans*') ? 'active' : '' }}"
               href="{{ route('scans.index') }}">
                <i class="fas fa-history"></i>
                <span>Riwayat Scan</span>
            </a>
        </li>

        <!-- Notifikasi -->
        <li class="nav-item">
            <a class="nav-link {{ request()->is('notifications*', 'admin/notifications*') ? 'active' : '' }}"
               href="{{ route('admin.notifications.index') }}">
                <i class="fas fa-bell"></i>
                <span>Notifikasi</span>
            </a>
        </li>

    </ul>

    <div class="sidebar-footer">
        @auth
        <div class="user-info">
            <div class="user-avatar">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="user-details">
                <div class="user-name">{{ Auth::user()->name }}</div>
                <div class="user-role">Administrator</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </button>
        </form>
        @else
        <a href="{{ route('login') }}" class="btn-logout" style="text-decoration: none;">
            <i class="fas fa-sign-in-alt"></i>
            <span>Login</span>
        </a>
        @endauth
    </div>
</div>

<!-- Content -->
<div class="content fade-in-up">
    @yield('content')
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Smooth animations
            const elements = document.querySelectorAll('.card, .nav-link, .stats-card');
            elements.forEach((element, index) => {
                element.style.opacity = '0';
                element.style.transform = 'translateY(20px)';
                element.style.transition = 'all 0.5s ease';

                setTimeout(() => {
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                }, 100 + (index * 100));
            });

            // Active state management
            const currentPath = window.location.pathname;
            document.querySelectorAll('.nav-link').forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                }
            });

            // Submenu toggle animation
            const submenuToggles = document.querySelectorAll('[data-bs-toggle="collapse"]');
            submenuToggles.forEach(toggle => {
                toggle.addEventListener('click', function() {
                    const icon = this.querySelector('.fa-chevron-down');
                    icon.classList.toggle('fa-rotate-180');
                });
            });
        });
    </script>
</body>

</html>
