<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - MediCare Pro</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        body {
            background: linear-gradient(135deg, #0891b2 0%, #064e5b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
        }
        .login-wrap {
            display: flex;
            width: 100%;
            max-width: 900px;
            min-height: 520px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 24px 60px rgba(0,0,0,0.3);
            margin: 20px;
        }
        /* Panneau gauche */
        .login-panel {
            flex: 1;
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(10px);
            padding: 48px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            color: #fff;
        }
        .login-panel-logo {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 40px;
        }
        .login-panel-logo h1 { font-size: 1.6rem; font-weight: 700; margin: 0; }
        .login-panel-logo span { font-size: 0.85rem; opacity: 0.75; }
        .login-panel h2 { font-size: 1.5rem; font-weight: 600; margin: 0 0 10px; }
        .login-panel p { opacity: 0.75; font-size: 0.9rem; line-height: 1.6; margin: 0 0 32px; }
        .features { display: flex; flex-direction: column; gap: 14px; }
        .feature-item {
            display: flex; align-items: center; gap: 12px;
            background: rgba(255,255,255,0.1);
            border-radius: 10px; padding: 12px 16px;
        }
        .feature-item svg { flex-shrink: 0; opacity: 0.9; }
        .feature-item span { font-size: 0.875rem; font-weight: 500; }
        /* Panneau droit */
        .login-form-panel {
            width: 380px;
            background: #fff;
            padding: 48px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .login-form-panel h2 { font-size: 1.4rem; font-weight: 700; color: #1e293b; margin: 0 0 6px; }
        .login-form-panel p { color: #64748b; font-size: 0.875rem; margin: 0 0 28px; }
        .form-group { margin-bottom: 18px; }
        .form-label { display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 6px; }
        .form-control {
            width: 100%; padding: 11px 14px; border: 1.5px solid #e2e8f0;
            border-radius: 10px; font-size: 0.9rem; color: #1e293b;
            transition: border-color 0.2s, box-shadow 0.2s; outline: none;
            box-sizing: border-box; font-family: 'Inter', sans-serif;
        }
        .form-control:focus { border-color: #0891b2; box-shadow: 0 0 0 3px rgba(8,145,178,0.12); }
        .form-control.error { border-color: #ef4444; }
        .error-msg { color: #ef4444; font-size: 0.8rem; margin-top: 4px; }
        .btn-login {
            width: 100%; padding: 13px; background: #0891b2; color: #fff;
            border: none; border-radius: 10px; font-size: 0.95rem; font-weight: 600;
            cursor: pointer; display: flex; align-items: center; justify-content: center;
            gap: 8px; transition: background 0.2s, transform 0.1s; margin-top: 8px;
            font-family: 'Inter', sans-serif;
        }
        .btn-login:hover { background: #0e7490; }
        .btn-login:active { transform: scale(0.99); }
        .alert-error {
            background: #fef2f2; border: 1px solid #fca5a5; color: #991b1b;
            padding: 12px 14px; border-radius: 10px; margin-bottom: 20px;
            font-size: 0.875rem; display: flex; align-items: center; gap: 8px;
        }
        .divider { text-align: center; color: #94a3b8; font-size: 0.8rem; margin: 20px 0; position: relative; }
        .divider::before, .divider::after {
            content: ''; position: absolute; top: 50%; width: 40%; height: 1px; background: #e2e8f0;
        }
        .divider::before { left: 0; }
        .divider::after { right: 0; }
        .demo-accounts { display: flex; flex-direction: column; gap: 6px; }
        .demo-btn {
            padding: 8px 12px; border: 1.5px solid #e2e8f0; border-radius: 8px;
            font-size: 0.78rem; color: #475569; cursor: pointer; background: #f8fafc;
            transition: all 0.2s; text-align: left; font-family: 'Inter', sans-serif;
            display: flex; align-items: center; gap: 8px;
        }
        .demo-btn:hover { border-color: #0891b2; background: #f0f9ff; color: #0891b2; }
        .demo-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
        @media(max-width: 640px) {
            .login-panel { display: none; }
            .login-form-panel { width: 100%; border-radius: 20px; }
            .login-wrap { border-radius: 20px; }
        }
    </style>
</head>
<body>
    <div class="login-wrap">
        <!-- Panneau gauche -->
        <div class="login-panel">
            <div class="login-panel-logo">
                <svg viewBox="0 0 44 44" fill="none" width="44" height="44"><rect width="44" height="44" rx="10" fill="rgba(255,255,255,0.2)"/><path d="M22 10v24M10 22h24" stroke="#fff" stroke-width="3.5" stroke-linecap="round"/></svg>
                <div>
                    <h1>MediCare Pro</h1>
                    <span>Système de Gestion Hospitalière</span>
                </div>
            </div>
            <h2>Bienvenue</h2>
            <p>Plateforme intégrée de gestion hospitalière pour une prise en charge optimale des patients.</p>
            <div class="features">
                <div class="feature-item">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                    <span>Gestion patients & dossiers médicaux</span>
                </div>
                <div class="feature-item">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                    <span>Consultations & suivi médical</span>
                </div>
                <div class="feature-item">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0016.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 002 8.5c0 2.3 1.5 4.05 3 5.5l7 7z"/></svg>
                    <span>Pharmacie & stock médicaments</span>
                </div>
                <div class="feature-item">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><path d="M1 10h22"/></svg>
                    <span>Caisse & facturation</span>
                </div>
            </div>
        </div>

        <!-- Formulaire -->
        <div class="login-form-panel">
            <h2>Connexion</h2>
            <p>Accédez à votre espace de travail</p>

            @if ($errors->any())
            <div class="alert-error">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="email">Adresse email</label>
                    <input type="email" id="email" name="email" class="form-control {{ $errors->has('email') ? 'error' : '' }}"
                        placeholder="exemple@medicare.ci" value="{{ old('email') }}" required autofocus autocomplete="email">
                    @error('email') <div class="error-msg">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" class="form-control {{ $errors->has('password') ? 'error' : '' }}"
                        placeholder="••••••••" required autocomplete="current-password">
                    @error('password') <div class="error-msg">{{ $message }}</div> @enderror
                </div>
                <button type="submit" class="btn-login">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4M10 17l5-5-5-5M15 12H3"/></svg>
                    Se connecter
                </button>
            </form>

            <div class="divider">Accès rapide démo</div>

            <div class="demo-accounts">
                <button class="demo-btn" onclick="fillDemo('admin@medicare.ci')">
                    <span class="demo-dot" style="background:#f59e0b;"></span>
                    <strong>Administrateur</strong> — admin@medicare.ci
                </button>
                <button class="demo-btn" onclick="fillDemo('reception@medicare.ci')">
                    <span class="demo-dot" style="background:#0891b2;"></span>
                    <strong>Réception</strong> — reception@medicare.ci
                </button>
                <button class="demo-btn" onclick="fillDemo('medecin@medicare.ci')">
                    <span class="demo-dot" style="background:#7c3aed;"></span>
                    <strong>Médecin</strong> — medecin@medicare.ci
                </button>
                <button class="demo-btn" onclick="fillDemo('caisse@medicare.ci')">
                    <span class="demo-dot" style="background:#059669;"></span>
                    <strong>Caisse</strong> — caisse@medicare.ci
                </button>
                <button class="demo-btn" onclick="fillDemo('pharmacie@medicare.ci')">
                    <span class="demo-dot" style="background:#dc2626;"></span>
                    <strong>Pharmacie</strong> — pharmacie@medicare.ci
                </button>
            </div>
        </div>
    </div>

    <script>
    function fillDemo(email) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = 'password';
        document.getElementById('password').form.submit();
    }
    </script>
</body>
</html>
