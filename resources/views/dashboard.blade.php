<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - MediCare Pro</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        body {
            background: #f1f5f9;
            font-family: 'Inter', sans-serif;
            margin: 0; min-height: 100vh;
        }

        /* ─── Top bar ─── */
        .topbar {
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: 0 32px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky; top: 0; z-index: 50;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        }
        .topbar-logo {
            display: flex; align-items: center; gap: 12px;
        }
        .topbar-logo h1 { font-size: 1.2rem; font-weight: 700; color: #0f172a; margin: 0; }
        .topbar-logo span { font-size: 0.78rem; color: #64748b; }
        .topbar-right { display: flex; align-items: center; gap: 16px; }
        .user-chip {
            display: flex; align-items: center; gap: 10px;
            background: #f8fafc; border: 1px solid #e2e8f0;
            border-radius: 40px; padding: 6px 14px 6px 6px;
        }
        .user-chip-avatar {
            width: 32px; height: 32px; border-radius: 50%;
            background: #0891b2; color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.75rem; font-weight: 700;
        }
        .user-chip-name { font-size: 0.875rem; font-weight: 600; color: #1e293b; }
        .user-chip-role { font-size: 0.72rem; color: #64748b; }
        .btn-logout {
            padding: 8px 16px; border: 1.5px solid #e2e8f0;
            border-radius: 8px; background: #fff; color: #475569;
            font-size: 0.85rem; font-weight: 500; cursor: pointer;
            display: flex; align-items: center; gap: 6px;
            transition: all 0.2s; font-family: 'Inter', sans-serif;
        }
        .btn-logout:hover { border-color: #ef4444; color: #ef4444; background: #fef2f2; }

        /* ─── Content ─── */
        .content { max-width: 1100px; margin: 0 auto; padding: 40px 24px; }

        .welcome-section { margin-bottom: 36px; }
        .welcome-section h2 { font-size: 1.75rem; font-weight: 700; color: #0f172a; margin: 0 0 6px; }
        .welcome-section p { color: #64748b; font-size: 0.95rem; margin: 0; }

        /* ─── Admin stats ─── */
        .admin-stats {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 16px;
            margin-bottom: 36px;
        }
        .stat-mini {
            background: #fff;
            border-radius: 12px;
            padding: 18px 20px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .stat-mini-label { font-size: 0.75rem; color: #64748b; font-weight: 500; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.04em; }
        .stat-mini-value { font-size: 1.5rem; font-weight: 700; color: #0f172a; }
        .stat-mini-sub { font-size: 0.75rem; color: #94a3b8; margin-top: 3px; }

        /* ─── Module grid ─── */
        .section-title {
            font-size: 0.8rem; font-weight: 600; color: #94a3b8;
            text-transform: uppercase; letter-spacing: 0.08em;
            margin-bottom: 16px;
        }
        .modules-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 18px;
        }
        .module-card {
            background: #fff;
            border-radius: 16px;
            padding: 28px 24px;
            border: 2px solid #e2e8f0;
            text-decoration: none;
            display: flex; flex-direction: column; align-items: flex-start; gap: 14px;
            transition: all 0.2s;
            position: relative;
            overflow: hidden;
        }
        .module-card.accessible:hover {
            border-color: var(--card-color);
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
            transform: translateY(-2px);
        }
        .module-card.locked {
            opacity: 0.45;
            cursor: not-allowed;
            background: #f8fafc;
            pointer-events: none;
        }
        .module-icon {
            width: 52px; height: 52px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            background: var(--card-light);
        }
        .module-icon svg { color: var(--card-color); }
        .module-name { font-size: 1.05rem; font-weight: 700; color: #0f172a; margin: 0; }
        .module-desc { font-size: 0.8rem; color: #64748b; margin: 0; line-height: 1.5; }
        .module-badge {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 4px 10px; border-radius: 20px;
            font-size: 0.72rem; font-weight: 600;
        }
        .badge-access {
            background: var(--card-light); color: var(--card-color);
        }
        .badge-locked {
            background: #f1f5f9; color: #94a3b8;
        }
        .module-arrow {
            position: absolute; right: 18px; top: 50%;
            transform: translateY(-50%); opacity: 0;
            transition: opacity 0.2s, right 0.2s;
            color: var(--card-color);
        }
        .module-card.accessible:hover .module-arrow { opacity: 1; right: 14px; }
    </style>
</head>
<body>

<!-- Top bar -->
<div class="topbar">
    <div class="topbar-logo">
        <svg viewBox="0 0 36 36" fill="none" width="36" height="36">
            <rect width="36" height="36" rx="8" fill="#0891b2"/>
            <path d="M18 8v20M8 18h20" stroke="#fff" stroke-width="3" stroke-linecap="round"/>
        </svg>
        <div>
            <h1>MediCare Pro</h1>
            <span>Système de Gestion Hospitalière</span>
        </div>
    </div>
    <div class="topbar-right">
        <div class="user-chip">
            <div class="user-chip-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
            <div>
                <div class="user-chip-name">{{ auth()->user()->name }}</div>
                <div class="user-chip-role">{{ ucfirst(auth()->user()->role) }}</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/></svg>
                Déconnexion
            </button>
        </form>
    </div>
</div>

<div class="content">

    <!-- Welcome -->
    <div class="welcome-section">
        <h2>Bonjour, {{ explode(' ', auth()->user()->name)[0] }} 👋</h2>
        <p>Sélectionnez votre espace de travail ci-dessous.</p>
    </div>

    @php
    $role = auth()->user()->role;

    $modules = [
        [
            'key'     => 'reception',
            'name'    => 'Réception',
            'desc'    => 'Accueil, enregistrement des patients et gestion des consultations.',
            'route'   => 'reception.index',
            'color'   => '#0891b2',
            'light'   => '#e0f2fe',
            'icon'    => '<path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>',
            'roles'   => ['admin', 'reception'],
        ],
        [
            'key'     => 'medecin',
            'name'    => 'Médecin',
            'desc'    => 'File d\'attente, consultations et dossiers médicaux.',
            'route'   => 'medecin.index',
            'color'   => '#7c3aed',
            'light'   => '#ede9fe',
            'icon'    => '<path d="M22 12h-4l-3 9L9 3l-3 9H2"/>',
            'roles'   => ['admin', 'medecin'],
        ],
        [
            'key'     => 'caisse',
            'name'    => 'Caisse',
            'desc'    => 'Paiements, factures et gestion financière.',
            'route'   => 'caisse.index',
            'color'   => '#059669',
            'light'   => '#d1fae5',
            'icon'    => '<rect x="1" y="4" width="22" height="16" rx="2"/><path d="M1 10h22"/>',
            'roles'   => ['admin', 'caisse'],
        ],
        [
            'key'     => 'pharmacie',
            'name'    => 'Pharmacie',
            'desc'    => 'Gestion du stock de médicaments et dispensation.',
            'route'   => 'pharmacie.index',
            'color'   => '#dc2626',
            'light'   => '#fee2e2',
            'icon'    => '<path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0016.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 002 8.5c0 2.3 1.5 4.05 3 5.5l7 7z"/>',
            'roles'   => ['admin', 'pharmacie'],
        ],
        [
            'key'     => 'admin',
            'name'    => 'Administration',
            'desc'    => 'Médecins, planning, hospitalisation et caisse.',
            'route'   => 'admin.medecins',
            'color'   => '#d97706',
            'light'   => '#fef3c7',
            'icon'    => '<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-2 2 2 2 0 01-2-2v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83 0 2 2 0 010-2.83l.06-.06A1.65 1.65 0 004.6 9a1.65 1.65 0 00-1.51-1H3a2 2 0 01-2-2 2 2 0 012-2h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00.33-1.82l-.06-.06a2 2 0 010-2.83 2 2 0 012.83 0l.06.06a1.65 1.65 0 001.82.33H9a1.65 1.65 0 001-1.51V3a2 2 0 012-2 2 2 0 012 2v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 0 2 2 0 010 2.83l-.06.06a1.65 1.65 0 00-.33 1.82V9a1.65 1.65 0 001.51 1H21a2 2 0 012 2 2 2 0 01-2 2h-.09a1.65 1.65 0 00-1.51 1z"/>',
            'roles'   => ['admin'],
        ],
    ];
    @endphp

    {{-- Stats admin --}}
    @if($stats)
    <div class="admin-stats">
        <div class="stat-mini">
            <div class="stat-mini-label">Patients</div>
            <div class="stat-mini-value">{{ $stats['patients_total'] }}</div>
            <div class="stat-mini-sub">{{ $stats['patients_hospitalises'] }} hospitalisés</div>
        </div>
        <div class="stat-mini">
            <div class="stat-mini-label">Consultations</div>
            <div class="stat-mini-value">{{ $stats['consultations_jour'] }}</div>
            <div class="stat-mini-sub">{{ $stats['consultations_attente'] }} en attente</div>
        </div>
        <div class="stat-mini">
            <div class="stat-mini-label">Médecins</div>
            <div class="stat-mini-value">{{ $stats['medecins_total'] }}</div>
            <div class="stat-mini-sub">{{ $stats['medecins_disponibles'] }} disponibles</div>
        </div>
        <div class="stat-mini">
            <div class="stat-mini-label">Recettes du jour</div>
            <div class="stat-mini-value" style="font-size:1.2rem;">{{ number_format($stats['recettes_jour'], 0, ',', ' ') }} F</div>
            <div class="stat-mini-sub">{{ number_format($stats['factures_impayees'], 0, ',', ' ') }} F impayés</div>
        </div>
        <div class="stat-mini">
            <div class="stat-mini-label">Chambres</div>
            <div class="stat-mini-value">{{ $stats['chambres_occupees'] }}/{{ $stats['chambres_total'] }}</div>
            <div class="stat-mini-sub">{{ $stats['occupation'] }}% occupation</div>
        </div>
    </div>
    @endif

    <!-- Modules -->
    <div class="section-title">Espaces de travail</div>
    <div class="modules-grid">
        @foreach($modules as $module)
        @php $hasAccess = in_array($role, $module['roles']); @endphp

        @if($hasAccess)
        <a href="{{ route($module['route']) }}"
           class="module-card accessible"
           style="--card-color:{{ $module['color'] }};--card-light:{{ $module['light'] }};">
        @else
        <div class="module-card locked"
             style="--card-color:#94a3b8;--card-light:#f1f5f9;">
        @endif

            <div class="module-icon">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="var(--card-color)" stroke-width="2">
                    {!! $module['icon'] !!}
                </svg>
            </div>
            <div>
                <p class="module-name">{{ $module['name'] }}</p>
                <p class="module-desc">{{ $module['desc'] }}</p>
            </div>
            <span class="module-badge {{ $hasAccess ? 'badge-access' : 'badge-locked' }}">
                @if($hasAccess)
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><path d="M22 4L12 14.01l-3-3"/></svg>
                    Accès autorisé
                @else
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                    Accès restreint
                @endif
            </span>

            @if($hasAccess)
            <svg class="module-arrow" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            @endif

        @if($hasAccess)
        </a>
        @else
        </div>
        @endif

        @endforeach
    </div>
</div>

</body>
</html>
