<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - MediCare Pro</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', system-ui, sans-serif;
            min-height: 100vh;
            display: flex;
            background: #f1f5f9;
        }

        /* ─── Left Panel (Hero / Branding) ─── */
        .login-hero {
            position: relative;
            width: 60%;
            min-height: 100vh;
            background: linear-gradient(135deg, #0891b2 0%, #0e7490 40%, #164e63 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px 64px;
            overflow: hidden;
        }

        /* Radial light overlays */
        .login-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 20% 50%, rgba(255,255,255,.08) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255,255,255,.06) 0%, transparent 40%),
                radial-gradient(circle at 60% 80%, rgba(255,255,255,.04) 0%, transparent 40%);
            pointer-events: none;
        }

        /* Medical cross pattern overlay */
        .login-hero::after {
            content: '';
            position: absolute;
            inset: 0;
            opacity: .04;
            background-image:
                url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Crect x='26' y='10' width='8' height='40' rx='2' fill='%23fff'/%3E%3Crect x='10' y='26' width='40' height='8' rx='2' fill='%23fff'/%3E%3C/svg%3E");
            background-size: 60px 60px;
            pointer-events: none;
        }

        /* Floating circle decorations */
        .shape {
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,.06);
            pointer-events: none;
        }
        .shape-1 { width: 340px; height: 340px; top: -100px; right: -80px; }
        .shape-2 { width: 220px; height: 220px; bottom: -40px; left: -60px; }
        .shape-3 { width: 140px; height: 140px; top: 35%; right: 15%; background: rgba(255,255,255,.03); }
        .shape-4 { width: 80px; height: 80px; bottom: 25%; left: 20%; background: rgba(255,255,255,.05); }

        .hero-content {
            position: relative;
            z-index: 2;
            color: #fff;
        }

        .hero-logo {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 48px;
        }

        .hero-logo-icon {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            background: rgba(255,255,255,.2);
            backdrop-filter: blur(10px);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .hero-logo-text h1 {
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: -.03em;
            margin: 0;
            line-height: 1.1;
        }

        .hero-logo-text span {
            font-size: 0.9rem;
            color: rgba(255,255,255,.65);
            font-weight: 500;
            display: block;
            margin-top: 4px;
        }

        .hero-tagline {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 12px;
            line-height: 1.3;
        }

        .hero-description {
            font-size: 1rem;
            color: rgba(255,255,255,.7);
            line-height: 1.7;
            margin-bottom: 40px;
            max-width: 440px;
        }

        .hero-features {
            display: flex;
            flex-direction: column;
            gap: 14px;
            margin-bottom: 48px;
        }

        .hero-feature {
            display: flex;
            align-items: center;
            gap: 14px;
            background: rgba(255,255,255,.1);
            backdrop-filter: blur(4px);
            border-radius: 12px;
            padding: 14px 18px;
            transition: background 0.2s;
        }

        .hero-feature:hover {
            background: rgba(255,255,255,.15);
        }

        .hero-feature-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: rgba(255,255,255,.15);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .hero-feature span {
            font-size: 0.9rem;
            font-weight: 600;
            color: #fff;
        }

        .hero-footer {
            position: relative;
            z-index: 2;
            color: rgba(255,255,255,.4);
            font-size: 0.8rem;
            margin-top: auto;
            padding-top: 32px;
        }

        /* ─── Right Panel (Login Form) ─── */
        .login-form-side {
            width: 40%;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background: #f8fafc;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            background: #fff;
            border-radius: 20px;
            padding: 48px 40px;
            box-shadow:
                0 1px 3px rgba(0,0,0,.04),
                0 8px 24px rgba(0,0,0,.06),
                0 24px 48px rgba(0,0,0,.04);
        }

        .login-card h2 {
            font-size: 1.65rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 6px;
            letter-spacing: -.02em;
        }

        .login-card .subtitle {
            color: #64748b;
            font-size: 0.9rem;
            margin-bottom: 32px;
            line-height: 1.5;
        }

        /* Alert error */
        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 10px;
            line-height: 1.4;
        }

        .alert-error svg {
            flex-shrink: 0;
        }

        /* Form elements */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            pointer-events: none;
            display: flex;
            align-items: center;
        }

        .form-control {
            width: 100%;
            padding: 13px 14px 13px 44px;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.9rem;
            color: #1e293b;
            background: #fff;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
            font-family: 'Inter', sans-serif;
        }

        .form-control::placeholder {
            color: #94a3b8;
        }

        .form-control:focus {
            border-color: #0891b2;
            box-shadow: 0 0 0 4px rgba(8,145,178,.1);
        }

        .form-control.error {
            border-color: #ef4444;
            box-shadow: 0 0 0 4px rgba(239,68,68,.08);
        }

        .error-msg {
            color: #ef4444;
            font-size: 0.78rem;
            margin-top: 6px;
            font-weight: 500;
        }

        /* Remember me */
        .remember-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 24px;
        }

        .remember-row input[type="checkbox"] {
            width: 18px;
            height: 18px;
            border-radius: 5px;
            border: 1.5px solid #d1d5db;
            accent-color: #0891b2;
            cursor: pointer;
        }

        .remember-row label {
            font-size: 0.85rem;
            color: #64748b;
            cursor: pointer;
            user-select: none;
        }

        /* Submit button */
        .btn-login {
            width: 100%;
            padding: 14px 24px;
            background: linear-gradient(135deg, #0891b2 0%, #0e7490 50%, #164e63 100%);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: transform 0.15s, box-shadow 0.2s, opacity 0.2s;
            font-family: 'Inter', sans-serif;
            letter-spacing: -.01em;
            box-shadow: 0 4px 14px rgba(8,145,178,.35);
        }

        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(8,145,178,.4);
        }

        .btn-login:active {
            transform: translateY(0) scale(0.99);
        }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            gap: 16px;
            margin: 28px 0 20px;
            color: #94a3b8;
            font-size: 0.78rem;
            font-weight: 500;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        /* Demo accounts */
        .demo-accounts {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .demo-btn {
            padding: 9px 14px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.78rem;
            color: #475569;
            cursor: pointer;
            background: #f8fafc;
            transition: all 0.2s;
            text-align: left;
            font-family: 'Inter', sans-serif;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .demo-btn:hover {
            border-color: #0891b2;
            background: #f0f9ff;
            color: #0891b2;
            transform: translateX(2px);
        }

        .demo-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .demo-info {
            margin-top: 16px;
            padding: 12px 16px;
            background: #f0f9ff;
            border-radius: 10px;
            border: 1px solid #bae6fd;
        }

        .demo-info p {
            font-size: 0.75rem;
            color: #0369a1;
            margin: 0;
            line-height: 1.6;
        }

        .demo-info p strong {
            font-weight: 700;
        }

        /* ─── Responsive ─── */
        @media (max-width: 1024px) {
            .login-hero {
                width: 50%;
                padding: 48px 40px;
            }
            .login-form-side {
                width: 50%;
            }
        }

        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }
            .login-hero {
                display: none;
            }
            .login-form-side {
                width: 100%;
                min-height: 100vh;
                background: linear-gradient(180deg, #f0f9ff 0%, #f8fafc 100%);
                padding: 24px;
            }
            .login-card {
                padding: 36px 28px;
            }
        }

        /* Loading animation on button */
        .btn-login.loading {
            opacity: 0.85;
            pointer-events: none;
        }

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

        .login-card {
            animation: fadeInUp 0.5s ease-out;
        }

        .hero-content {
            animation: fadeInUp 0.6s ease-out;
        }
    </style>
</head>
<body>

    <!-- ═══════════════════════════════════════════════════════ -->
    <!-- Left Panel: Hero / Branding                            -->
    <!-- ═══════════════════════════════════════════════════════ -->
    <div class="login-hero">
        <!-- Floating circle shapes -->
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
        <div class="shape shape-4"></div>

        <div class="hero-content">
            <!-- Logo -->
            <div class="hero-logo">
                <div class="hero-logo-icon">
                    <svg viewBox="0 0 32 32" fill="none" width="28" height="28">
                        <path d="M16 4v24M4 16h24" stroke="#fff" stroke-width="3.5" stroke-linecap="round"/>
                    </svg>
                </div>
                <div class="hero-logo-text">
                    <h1>MediCare Pro</h1>
                    <span>Système de Gestion Hospitalière</span>
                </div>
            </div>

            <!-- Tagline -->
            <div class="hero-tagline">Votre plateforme intégrée de gestion hospitalière</div>
            <p class="hero-description">
                Une solution complète pour la prise en charge optimale des patients, du suivi médical à la facturation.
            </p>

            <!-- Feature bullets -->
            <div class="hero-features">
                <div class="hero-feature">
                    <div class="hero-feature-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 00-3-3.87"/>
                            <path d="M16 3.13a4 4 0 010 7.75"/>
                        </svg>
                    </div>
                    <span>Gestion des patients</span>
                </div>
                <div class="hero-feature">
                    <div class="hero-feature-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                        </svg>
                    </div>
                    <span>Suivi médical complet</span>
                </div>
                <div class="hero-feature">
                    <div class="hero-feature-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="5" width="20" height="14" rx="2"/>
                            <path d="M2 10h20"/>
                        </svg>
                    </div>
                    <span>Facturation intelligente</span>
                </div>
                <div class="hero-feature">
                    <div class="hero-feature-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0016.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 002 8.5c0 2.3 1.5 4.05 3 5.5l7 7z"/>
                        </svg>
                    </div>
                    <span>Pharmacie intégrée</span>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="hero-footer">
            &copy; 2026 MediCare Pro. Tous droits réservés.
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════ -->
    <!-- Right Panel: Login Form                                -->
    <!-- ═══════════════════════════════════════════════════════ -->
    <div class="login-form-side">
        <div class="login-card">
            <h2>Bienvenue</h2>
            <p class="subtitle">Connectez-vous à votre espace</p>

            {{-- Error messages --}}
            @if ($errors->any())
                <div class="alert-error">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M12 8v4M12 16h.01"/>
                    </svg>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf

                {{-- Email --}}
                <div class="form-group">
                    <label class="form-label" for="email">Adresse email</label>
                    <div class="input-wrapper">
                        <span class="input-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="4" width="20" height="16" rx="2"/>
                                <path d="M22 7l-10 7L2 7"/>
                            </svg>
                        </span>
                        <input type="email" id="email" name="email"
                            class="form-control {{ $errors->has('email') ? 'error' : '' }}"
                            placeholder="exemple@medicare.ci"
                            value="{{ old('email') }}"
                            required autofocus autocomplete="email">
                    </div>
                    @error('email') <div class="error-msg">{{ $message }}</div> @enderror
                </div>

                {{-- Password --}}
                <div class="form-group">
                    <label class="form-label" for="password">Mot de passe</label>
                    <div class="input-wrapper">
                        <span class="input-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0110 0v4"/>
                            </svg>
                        </span>
                        <input type="password" id="password" name="password"
                            class="form-control {{ $errors->has('password') ? 'error' : '' }}"
                            placeholder="••••••••"
                            required autocomplete="current-password">
                    </div>
                    @error('password') <div class="error-msg">{{ $message }}</div> @enderror
                </div>

                {{-- Remember me --}}
                <div class="remember-row">
                    <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember">Se souvenir de moi</label>
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn-login" id="btnLogin">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4"/>
                        <polyline points="10 17 15 12 10 7"/>
                        <line x1="15" y1="12" x2="3" y2="12"/>
                    </svg>
                    Se connecter
                </button>
            </form>

            {{-- Demo quick access --}}
            <div class="divider">Accès rapide démo</div>

            <div class="demo-accounts">
                <button class="demo-btn" onclick="fillDemo('admin@medicare.ci', 'Administrateur')">
                    <span class="demo-dot" style="background:#f59e0b;"></span>
                    <strong>Administrateur</strong>&nbsp;— admin@medicare.ci
                </button>
                <button class="demo-btn" onclick="fillDemo('reception@medicare.ci', 'Réception')">
                    <span class="demo-dot" style="background:#0891b2;"></span>
                    <strong>Réception</strong>&nbsp;— reception@medicare.ci
                </button>
                <button class="demo-btn" onclick="fillDemo('medecin@medicare.ci', 'Médecin')">
                    <span class="demo-dot" style="background:#7c3aed;"></span>
                    <strong>Médecin</strong>&nbsp;— medecin@medicare.ci
                </button>
                <button class="demo-btn" onclick="fillDemo('caisse@medicare.ci', 'Caisse')">
                    <span class="demo-dot" style="background:#059669;"></span>
                    <strong>Caisse</strong>&nbsp;— caisse@medicare.ci
                </button>
                <button class="demo-btn" onclick="fillDemo('pharmacie@medicare.ci', 'Pharmacie')">
                    <span class="demo-dot" style="background:#dc2626;"></span>
                    <strong>Pharmacie</strong>&nbsp;— pharmacie@medicare.ci
                </button>
            </div>

            {{-- Demo credentials info --}}
            <div class="demo-info">
                <p>
                    <strong>Comptes de démonstration :</strong><br>
                    admin@medicare.ci | reception@medicare.ci | medecin@medicare.ci<br>
                    caisse@medicare.ci | pharmacie@medicare.ci<br>
                    <strong>Mot de passe :</strong> password
                </p>
            </div>
        </div>
    </div>

    <script>
        function fillDemo(email, role) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = 'password';
            document.getElementById('loginForm').submit();
        }
    </script>
</body>
</html>
