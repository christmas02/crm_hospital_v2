<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - MediCare Pro</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', system-ui, sans-serif;
            min-height: 100vh;
            background: #0f172a;
            color: #1e293b;
        }

        /* ─── Hero background ─── */
        .hero-bg {
            position: relative;
            background: linear-gradient(135deg, #0891b2 0%, #0e7490 40%, #164e63 100%);
            padding-bottom: 120px;
            overflow: hidden;
        }
        .hero-bg::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 20% 50%, rgba(255,255,255,.08) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255,255,255,.06) 0%, transparent 40%),
                radial-gradient(circle at 60% 80%, rgba(255,255,255,.04) 0%, transparent 40%);
            pointer-events: none;
        }
        /* Motif pattern de croix médicales */
        .hero-bg::after {
            content: '';
            position: absolute;
            inset: 0;
            opacity: .04;
            background-image:
                url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Crect x='26' y='10' width='8' height='40' rx='2' fill='%23fff'/%3E%3Crect x='10' y='26' width='40' height='8' rx='2' fill='%23fff'/%3E%3C/svg%3E");
            background-size: 60px 60px;
            pointer-events: none;
        }
        /* Shapes flottantes */
        .shape {
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,.06);
            pointer-events: none;
        }
        .shape-1 { width: 300px; height: 300px; top: -80px; right: -60px; }
        .shape-2 { width: 200px; height: 200px; bottom: 20px; left: -40px; }
        .shape-3 { width: 120px; height: 120px; top: 40%; right: 20%; background: rgba(255,255,255,.03); }

        /* ─── Top bar ─── */
        .topbar {
            position: relative;
            z-index: 10;
            padding: 0 40px;
            height: 72px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .topbar-logo {
            display: flex; align-items: center; gap: 14px;
        }
        .topbar-logo-icon {
            width: 42px; height: 42px; border-radius: 12px;
            background: rgba(255,255,255,.2);
            backdrop-filter: blur(10px);
            display: flex; align-items: center; justify-content: center;
        }
        .topbar-logo h1 { font-size: 1.25rem; font-weight: 800; color: #fff; letter-spacing: -.02em; }
        .topbar-logo span { font-size: 0.72rem; color: rgba(255,255,255,.6); display: block; }
        .topbar-right { display: flex; align-items: center; gap: 14px; }
        .user-chip {
            display: flex; align-items: center; gap: 10px;
            background: rgba(255,255,255,.12);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,.15);
            border-radius: 40px; padding: 5px 16px 5px 5px;
        }
        .user-chip-avatar {
            width: 34px; height: 34px; border-radius: 50%;
            background: rgba(255,255,255,.25);
            color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.75rem; font-weight: 700;
        }
        .user-chip-name { font-size: 0.85rem; font-weight: 600; color: #fff; }
        .user-chip-role { font-size: 0.7rem; color: rgba(255,255,255,.6); }
        .btn-logout {
            padding: 8px 16px;
            border: 1.5px solid rgba(255,255,255,.2);
            border-radius: 10px;
            background: rgba(255,255,255,.1);
            backdrop-filter: blur(10px);
            color: rgba(255,255,255,.85);
            font-size: 0.82rem; font-weight: 500; cursor: pointer;
            display: flex; align-items: center; gap: 6px;
            transition: all 0.2s; font-family: 'Inter', sans-serif;
        }
        .btn-logout:hover { background: rgba(239,68,68,.2); border-color: rgba(239,68,68,.4); color: #fca5a5; }

        /* ─── Welcome ─── */
        .welcome {
            position: relative; z-index: 10;
            padding: 32px 40px 0;
        }
        .welcome h2 {
            font-size: 2rem; font-weight: 800; color: #fff;
            letter-spacing: -.03em; margin-bottom: 6px;
        }
        .welcome p { color: rgba(255,255,255,.65); font-size: 1rem; }

        /* ─── Content card area ─── */
        .content-area {
            position: relative; z-index: 10;
            max-width: 1200px;
            margin: -80px auto 0;
            padding: 0 32px 60px;
        }

        /* ─── Stats ─── */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 16px;
            margin-bottom: 32px;
        }
        .stat-card {
            background: #fff;
            border-radius: 16px;
            padding: 22px 24px;
            box-shadow: 0 4px 20px rgba(0,0,0,.06), 0 0 0 1px rgba(0,0,0,.03);
            position: relative;
            overflow: hidden;
            transition: transform .2s, box-shadow .2s;
        }
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(0,0,0,.1);
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 4px;
        }
        .stat-card.sc-cyan::before { background: linear-gradient(90deg, #0891b2, #22d3ee); }
        .stat-card.sc-purple::before { background: linear-gradient(90deg, #7c3aed, #a78bfa); }
        .stat-card.sc-green::before { background: linear-gradient(90deg, #059669, #34d399); }
        .stat-card.sc-orange::before { background: linear-gradient(90deg, #d97706, #fbbf24); }
        .stat-card.sc-red::before { background: linear-gradient(90deg, #dc2626, #f87171); }
        .stat-card-icon {
            width: 44px; height: 44px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 14px;
        }
        .stat-card-icon.ic-cyan { background: #ecfeff; color: #0891b2; }
        .stat-card-icon.ic-purple { background: #ede9fe; color: #7c3aed; }
        .stat-card-icon.ic-green { background: #d1fae5; color: #059669; }
        .stat-card-icon.ic-orange { background: #fef3c7; color: #d97706; }
        .stat-card-icon.ic-red { background: #fee2e2; color: #dc2626; }
        .stat-card-label { font-size: .72rem; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: .06em; margin-bottom: 6px; }
        .stat-card-value { font-size: 1.6rem; font-weight: 800; color: #0f172a; line-height: 1; }
        .stat-card-sub { font-size: .72rem; color: #94a3b8; margin-top: 6px; }

        /* ─── Section ─── */
        .section-label {
            font-size: 0.72rem; font-weight: 700; color: #94a3b8;
            text-transform: uppercase; letter-spacing: .1em;
            margin-bottom: 18px;
            display: flex; align-items: center; gap: 10px;
        }
        .section-label::after {
            content: '';
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, #e2e8f0, transparent);
        }

        /* ─── Module cards ─── */
        .modules-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(210px, 1fr));
            gap: 18px;
        }
        .module-card {
            background: #fff;
            border-radius: 18px;
            padding: 28px 24px 22px;
            border: 2px solid #e2e8f0;
            text-decoration: none;
            display: flex; flex-direction: column; align-items: flex-start;
            transition: all 0.25s;
            position: relative;
            overflow: hidden;
        }
        .module-card::before {
            content: '';
            position: absolute;
            top: 0; right: 0;
            width: 100px; height: 100px;
            border-radius: 50%;
            background: var(--card-light);
            opacity: .5;
            transform: translate(30%, -30%);
            transition: all .3s;
        }
        .module-card.accessible:hover::before {
            width: 200px; height: 200px;
            opacity: .35;
        }
        .module-card.accessible:hover {
            border-color: var(--card-color);
            box-shadow: 0 12px 32px rgba(0,0,0,.08);
            transform: translateY(-4px);
        }
        .module-card.locked {
            opacity: 0.4;
            cursor: not-allowed;
            pointer-events: none;
            background: #fafafa;
        }
        .module-icon {
            width: 52px; height: 52px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            background: var(--card-light);
            margin-bottom: 18px;
            position: relative;
            z-index: 1;
        }
        .module-icon svg { color: var(--card-color); }
        .module-name {
            font-size: 1.05rem; font-weight: 700; color: #0f172a;
            margin-bottom: 6px; position: relative; z-index: 1;
        }
        .module-desc {
            font-size: 0.78rem; color: #64748b; line-height: 1.55;
            margin-bottom: 16px; position: relative; z-index: 1;
        }
        .module-badge {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 5px 12px; border-radius: 20px;
            font-size: 0.7rem; font-weight: 600;
            position: relative; z-index: 1;
        }
        .badge-access {
            background: var(--card-light); color: var(--card-color);
        }
        .badge-locked {
            background: #f1f5f9; color: #94a3b8;
        }
        .module-arrow {
            position: absolute; right: 20px; top: 50%;
            transform: translateY(-50%); opacity: 0;
            transition: opacity 0.25s, right 0.25s;
            color: var(--card-color);
            z-index: 1;
        }
        .module-card.accessible:hover .module-arrow { opacity: 1; right: 16px; }

        /* ─── Footer ─── */
        .dash-footer {
            text-align: center;
            padding: 20px;
            color: #64748b;
            font-size: .75rem;
        }
        .dash-footer a { color: #0891b2; text-decoration: none; font-weight: 500; }

        /* ─── Responsive ─── */
        @media (max-width: 1024px) {
            .stats-row { grid-template-columns: repeat(3, 1fr); }
        }
        @media (max-width: 768px) {
            .topbar { padding: 0 20px; }
            .welcome { padding: 24px 20px 0; }
            .content-area { padding: 0 16px 40px; }
            .stats-row { grid-template-columns: repeat(2, 1fr); }
            .modules-grid { grid-template-columns: 1fr 1fr; }
            .welcome h2 { font-size: 1.5rem; }
        }
        @media (max-width: 480px) {
            .stats-row { grid-template-columns: 1fr; }
            .modules-grid { grid-template-columns: 1fr; }
            .user-chip-name, .user-chip-role { display: none; }
        }
    </style>
</head>
<body>

<div class="hero-bg">
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>
    <div class="shape shape-3"></div>

    <!-- Top bar -->
    <div class="topbar">
        <div class="topbar-logo">
            <div class="topbar-logo-icon">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round">
                    <path d="M12 4v16M4 12h16"/>
                </svg>
            </div>
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

    <!-- Welcome -->
    <div class="welcome">
        <h2>Bonjour, {{ explode(' ', auth()->user()->name)[0] }} 👋</h2>
        <p>Sélectionnez votre espace de travail ci-dessous.</p>
    </div>
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

<div class="content-area">

    {{-- Stats admin --}}
    @if($stats)
    <div class="stats-row">
        <div class="stat-card sc-cyan">
            <div class="stat-card-icon ic-cyan">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
            </div>
            <div class="stat-card-label">Patients</div>
            <div class="stat-card-value">{{ $stats['patients_total'] }}</div>
            <div class="stat-card-sub">{{ $stats['patients_hospitalises'] }} hospitalisés</div>
        </div>
        <div class="stat-card sc-purple">
            <div class="stat-card-icon ic-purple">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
            </div>
            <div class="stat-card-label">Consultations</div>
            <div class="stat-card-value">{{ $stats['consultations_jour'] }}</div>
            <div class="stat-card-sub">{{ $stats['consultations_attente'] }} en attente</div>
        </div>
        <div class="stat-card sc-green">
            <div class="stat-card-icon ic-green">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </div>
            <div class="stat-card-label">Médecins</div>
            <div class="stat-card-value">{{ $stats['medecins_total'] }}</div>
            <div class="stat-card-sub">{{ $stats['medecins_disponibles'] }} disponibles</div>
        </div>
        <div class="stat-card sc-orange">
            <div class="stat-card-icon ic-orange">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
            </div>
            <div class="stat-card-label">Recettes du jour</div>
            <div class="stat-card-value" style="font-size:1.3rem;">{{ number_format($stats['recettes_jour'], 0, ',', ' ') }} F</div>
            <div class="stat-card-sub">{{ number_format($stats['factures_impayees'], 0, ',', ' ') }} F impayés</div>
        </div>
        <div class="stat-card sc-red">
            <div class="stat-card-icon ic-red">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><path d="M9 22V12h6v10"/></svg>
            </div>
            <div class="stat-card-label">Chambres</div>
            <div class="stat-card-value">{{ $stats['chambres_occupees'] }}/{{ $stats['chambres_total'] }}</div>
            <div class="stat-card-sub">{{ $stats['occupation'] }}% occupation</div>
        </div>
    </div>
    @endif

    <!-- Modules -->
    <div class="section-label">Espaces de travail</div>
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
            <p class="module-name">{{ $module['name'] }}</p>
            <p class="module-desc">{{ $module['desc'] }}</p>
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

    <div class="dash-footer">
        <p>MediCare Pro &copy; {{ date('Y') }} &mdash; Système de Gestion Hospitalière</p>
    </div>
</div>

</body>
</html>
