<!DOCTYPE html>
<html lang="fr">
<head>
    <script>if(localStorage.getItem('darkMode')==='1')document.documentElement.setAttribute('data-theme','dark');</script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'MediCare Pro')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @if(isset($useCharts) && $useCharts)
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endif
    @stack('styles')
</head>
<body>
    <div class="app">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <svg viewBox="0 0 36 36" fill="none"><rect width="36" height="36" rx="8" fill="#0891b2"/><path d="M18 8v20M8 18h20" stroke="#fff" stroke-width="3" stroke-linecap="round"/></svg>
                    <div>
                        <h1>MediCare</h1>
                        <span>@yield('sidebar-subtitle', 'Gestion Hospitalière')</span>
                    </div>
                </div>
            </div>
            <nav class="sidebar-nav">
                @yield('sidebar-nav')
            </nav>
            <div class="sidebar-footer">
                <div class="user-box">
                    <div class="user-avatar" style="background:@yield('user-color', '#0891b2');">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
                    <div class="user-info">
                        <h4>{{ Auth::user()->name }}</h4>
                        <span>{{ ucfirst(Auth::user()->role) }}</span>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="mt-4">
                    @csrf
                    <button type="submit" style="width:100%;padding:10px 16px;border-radius:10px;background:rgba(220,38,38,.15);border:1px solid rgba(220,38,38,.3);color:#fca5a5;font-size:.82rem;font-weight:600;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;transition:all .2s;font-family:inherit;" onmouseover="this.style.background='rgba(220,38,38,.3)';this.style.color='#fff'" onmouseout="this.style.background='rgba(220,38,38,.15)';this.style.color='#fca5a5'">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/></svg>
                        Déconnexion
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main -->
        <main class="main">
            <header class="header">
                <div class="header-left">
                    <button class="menu-toggle" onclick="toggleSidebar()">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12h18M3 6h18M3 18h18"/></svg>
                    </button>
                    <h1 class="header-title">@yield('header-title', 'Tableau de bord')</h1>
                </div>
                <div class="header-right">
                    <button class="icon-btn" id="darkModeToggle" title="Mode sombre" onclick="toggleDarkMode()">
                        <svg id="darkModeIcon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
                    </button>
                    <div style="position:relative;" id="notifWrap">
                        <button class="icon-btn" onclick="toggleNotifications()" id="notifBtn">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
                            <span class="badge-dot" id="notifDot" style="display:none;position:absolute;top:4px;right:4px;width:8px;height:8px;background:#ef4444;border-radius:50%;"></span>
                        </button>
                        <div id="notifDropdown" style="display:none;position:absolute;top:100%;right:0;width:360px;background:var(--white);border:1.5px solid var(--gray-200);border-radius:12px;box-shadow:0 12px 30px rgba(0,0,0,.12);z-index:999;max-height:420px;overflow-y:auto;margin-top:8px;">
                            <div style="padding:14px 16px;border-bottom:1px solid var(--gray-200);display:flex;justify-content:space-between;align-items:center;">
                                <span style="font-weight:700;font-size:.9rem;">Notifications</span>
                                <button onclick="markAllRead()" style="background:none;border:none;color:var(--primary);font-size:.78rem;font-weight:600;cursor:pointer;">Tout marquer lu</button>
                            </div>
                            <div id="notifList" style="padding:4px 0;">
                                <div style="padding:20px;text-align:center;color:var(--gray-400);font-size:.85rem;">Chargement...</div>
                            </div>
                        </div>
                    </div>
                    <div class="global-search" style="position:relative;">
                        <div class="search-box">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                            <input type="text" id="globalSearchInput" placeholder="Rechercher..." autocomplete="off">
                        </div>
                        <div id="globalSearchResults" style="display:none;position:absolute;top:100%;left:0;right:0;min-width:350px;background:#fff;border:1.5px solid var(--gray-200);border-radius:0 0 12px 12px;box-shadow:0 12px 30px rgba(0,0,0,.12);z-index:999;max-height:400px;overflow-y:auto;"></div>
                    </div>
                    @yield('header-right')
                </div>
            </header>

            <div class="page">
                @if(session('success'))
                <div class="alert-item" style="background:var(--success-light);margin-bottom:20px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--success)" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><path d="M22 4L12 14.01l-3-3"/></svg>
                    <div class="alert-text"><div class="alert-title">{{ session('success') }}</div></div>
                </div>
                @endif

                @if(session('error'))
                <div class="alert-item" style="margin-bottom:20px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M15 9l-6 6M9 9l6 6"/></svg>
                    <div class="alert-text"><div class="alert-title">{{ session('error') }}</div></div>
                </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <script>
        // Dark mode
        function toggleDarkMode() {
            var isDark = document.documentElement.getAttribute('data-theme') === 'dark';
            if (isDark) {
                document.documentElement.removeAttribute('data-theme');
                localStorage.removeItem('darkMode');
                document.getElementById('darkModeIcon').innerHTML = '<path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>';
            } else {
                document.documentElement.setAttribute('data-theme', 'dark');
                localStorage.setItem('darkMode', '1');
                document.getElementById('darkModeIcon').innerHTML = '<circle cx="12" cy="12" r="5"/><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/>';
            }
        }

        // Update icon on load if dark mode is active
        if (document.documentElement.getAttribute('data-theme') === 'dark') {
            document.addEventListener('DOMContentLoaded', function() {
                var icon = document.getElementById('darkModeIcon');
                if (icon) icon.innerHTML = '<circle cx="12" cy="12" r="5"/><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/>';
            });
        }

        const toggleSidebar = () => document.getElementById('sidebar').classList.toggle('open');

        // Close modal on overlay click
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('modal-overlay')) e.target.classList.remove('active');
        });

        const openModal = (id) => document.getElementById(id).classList.add('active');
        const closeModal = (id) => document.getElementById(id).classList.remove('active');

        // CSRF token for AJAX
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // Double-click protection
        document.addEventListener('submit', function(e) {
            var form = e.target;
            if (form.dataset.submitted === 'true') {
                e.preventDefault();
                return;
            }
            form.dataset.submitted = 'true';
            var btn = form.querySelector('button[type="submit"]');
            if (btn) {
                btn.disabled = true;
                btn.dataset.originalText = btn.innerHTML;
                btn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="animation:spin 1s linear infinite;margin-right:6px;"><path d="M21 12a9 9 0 11-6.219-8.56"/></svg> Chargement...';
            }
        });

        // ===== GLOBAL SEARCH =====
        (function() {
            var searchInput = document.getElementById('globalSearchInput');
            var searchResults = document.getElementById('globalSearchResults');
            if (!searchInput) return;

            var searchTimeout;

            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                var q = this.value.trim();
                if (q.length < 2) {
                    searchResults.style.display = 'none';
                    return;
                }
                searchTimeout = setTimeout(function() {
                    fetch('/search?q=' + encodeURIComponent(q))
                        .then(r => r.json())
                        .then(data => {
                            if (data.results.length === 0) {
                                searchResults.innerHTML = '<div style="padding:16px;text-align:center;color:var(--gray-400);font-size:.85rem;">Aucun resultat pour "' + q + '"</div>';
                            } else {
                                var icons = {
                                    patient: '<path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>',
                                    medecin: '<path d="M22 12h-4l-3 9L9 3l-3 9H2"/>',
                                    medicament: '<path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0016.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 002 8.5c0 2.3 1.5 4.05 3 5.5l7 7z"/>',
                                };
                                searchResults.innerHTML = data.results.map(function(r) {
                                    var iconPath = icons[r.icon] || icons.patient;
                                    return '<a href="' + r.url + '" style="display:flex;align-items:center;gap:12px;padding:10px 14px;border-bottom:1px solid var(--gray-100);text-decoration:none;color:inherit;transition:background .15s;"' +
                                        ' onmouseover="this.style.background=\'var(--primary-light)\'" onmouseout="this.style.background=\'transparent\'">' +
                                        '<div style="width:36px;height:36px;border-radius:10px;background:var(--gray-100);display:flex;align-items:center;justify-content:center;flex-shrink:0;">' +
                                        '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--gray-500)" stroke-width="2">' + iconPath + '</svg></div>' +
                                        '<div style="flex:1;min-width:0;"><div style="font-size:.85rem;font-weight:600;color:var(--gray-800);">' + r.title + '</div>' +
                                        '<div style="font-size:.72rem;color:var(--gray-500);">' + r.type + (r.subtitle ? ' — ' + r.subtitle : '') + '</div></div>' +
                                        '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--gray-300)" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg></a>';
                                }).join('');
                            }
                            searchResults.style.display = 'block';
                        });
                }, 300);
            });

            searchInput.addEventListener('blur', function() {
                setTimeout(function() { searchResults.style.display = 'none'; }, 200);
            });

            searchInput.addEventListener('focus', function() {
                if (this.value.trim().length >= 2) {
                    this.dispatchEvent(new Event('input'));
                }
            });

            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    searchResults.style.display = 'none';
                    this.blur();
                }
            });
        })();

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl+K or Cmd+K = focus search
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                var searchInput = document.getElementById('globalSearchInput');
                if (searchInput) searchInput.focus();
            }
            // Escape = close any open modal
            if (e.key === 'Escape') {
                document.querySelectorAll('.modal-overlay.active').forEach(function(m) {
                    m.classList.remove('active');
                });
            }
        });

        // ===== NOTIFICATIONS =====
        function toggleNotifications() {
            var dd = document.getElementById('notifDropdown');
            if (dd.style.display === 'none') {
                dd.style.display = 'block';
                loadNotifications();
            } else {
                dd.style.display = 'none';
            }
        }

        function loadNotifications() {
            fetch('/notifications').then(r => r.json()).then(data => {
                var dot = document.getElementById('notifDot');
                dot.style.display = data.unread > 0 ? 'block' : 'none';

                var icons = {
                    'user-plus': '<path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="8.5" cy="7" r="4"/><path d="M20 8v6M23 11h-6"/>',
                    'check-circle': '<path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><path d="M22 4L12 14.01l-3-3"/>',
                    'alert': '<path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><path d="M12 9v4M12 17h.01"/>',
                };

                if (data.notifications.length === 0) {
                    document.getElementById('notifList').innerHTML = '<div style="padding:30px;text-align:center;color:var(--gray-400);font-size:.85rem;">Aucune notification</div>';
                    return;
                }

                document.getElementById('notifList').innerHTML = data.notifications.map(function(n) {
                    var iconPath = icons[n.data.icon] || icons['check-circle'];
                    var bg = n.read ? 'transparent' : 'var(--primary-light)';
                    return '<a href="' + (n.data.url || '#') + '" style="display:flex;gap:12px;padding:12px 16px;text-decoration:none;color:inherit;background:' + bg + ';border-bottom:1px solid var(--gray-100);">' +
                        '<div style="width:36px;height:36px;border-radius:10px;background:var(--gray-100);display:flex;align-items:center;justify-content:center;flex-shrink:0;">' +
                        '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2">' + iconPath + '</svg></div>' +
                        '<div style="flex:1;"><div style="font-size:.82rem;color:var(--gray-700);">' + n.data.message + '</div>' +
                        '<div style="font-size:.7rem;color:var(--gray-400);margin-top:2px;">' + n.time + '</div></div></a>';
                }).join('');
            });
        }

        function markAllRead() {
            fetch('/notifications/mark-read', { method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken } })
                .then(function() { loadNotifications(); });
        }

        // Check for new notifications every 30s
        setInterval(function() {
            fetch('/notifications').then(r => r.json()).then(data => {
                document.getElementById('notifDot').style.display = data.unread > 0 ? 'block' : 'none';
            });
        }, 30000);

        // Close dropdown on outside click
        document.addEventListener('click', function(e) {
            var wrap = document.getElementById('notifWrap');
            if (wrap && !wrap.contains(e.target)) {
                document.getElementById('notifDropdown').style.display = 'none';
            }
        });

        // Initial notification check
        document.addEventListener('DOMContentLoaded', function() {
            fetch('/notifications').then(r => r.json()).then(data => {
                document.getElementById('notifDot').style.display = data.unread > 0 ? 'block' : 'none';
            });
        });

        // Global helper: set value on a searchable select (hidden select + visible input)
        function setSearchableSelectValue(selectEl, value, displayText) {
            if (!selectEl) return;
            selectEl.value = value;
            var wrap = selectEl.closest('.ss-wrap');
            if (wrap) {
                var ssInput = wrap.querySelector('.ss-input');
                if (ssInput) ssInput.value = displayText || '';
            }
        }

        // ===== SEARCHABLE SELECT GLOBAL =====
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('select.form-control').forEach(function(sel) {
                // Skip selects with few options (<=4), inside templates, or with small inline size
                var opts = Array.from(sel.options).filter(function(o) { return o.value !== ''; });
                if (opts.length <= 4) return;
                // Skip if inside a template or hidden clone row
                if (sel.closest('[id*="template"]') || sel.closest('.hidden')) return;

                var wrap = document.createElement('div');
                wrap.className = 'ss-wrap';

                var input = document.createElement('input');
                input.type = 'text';
                input.className = 'form-control ss-input';
                input.placeholder = sel.options[0] && sel.options[0].value === '' ? sel.options[0].textContent : 'Rechercher...';
                input.autocomplete = 'off';

                // If select already has a selected value
                var selectedOpt = sel.options[sel.selectedIndex];
                if (selectedOpt && selectedOpt.value !== '') {
                    input.value = selectedOpt.textContent;
                }

                var dropdown = document.createElement('div');
                dropdown.className = 'ss-dropdown';

                sel.style.display = 'none';
                sel.parentNode.insertBefore(wrap, sel);
                wrap.appendChild(input);
                wrap.appendChild(dropdown);
                wrap.appendChild(sel);

                function renderList(query) {
                    var q = query.toLowerCase();
                    var html = '';
                    var count = 0;
                    opts.forEach(function(o) {
                        if (o.textContent.toLowerCase().includes(q)) {
                            var isActive = o.value === sel.value ? ' ss-item-active' : '';
                            html += '<div class="ss-item' + isActive + '" data-value="' + o.value + '">' + o.textContent + '</div>';
                            count++;
                        }
                    });
                    if (count === 0) {
                        html = '<div class="ss-empty">Aucun résultat</div>';
                    }
                    dropdown.innerHTML = html;
                    dropdown.style.display = 'block';

                    dropdown.querySelectorAll('.ss-item').forEach(function(item) {
                        item.addEventListener('mousedown', function(e) {
                            e.preventDefault();
                            sel.value = this.dataset.value;
                            input.value = this.textContent;
                            dropdown.style.display = 'none';
                            // Trigger change event on hidden select + input event for live filters
                            sel.dispatchEvent(new Event('change', { bubbles: true }));
                            input.dispatchEvent(new Event('input', { bubbles: true }));
                        });
                    });
                }

                input.addEventListener('focus', function() {
                    renderList(this.value === selectedOpt?.textContent ? '' : this.value);
                });

                input.addEventListener('input', function() {
                    sel.value = '';
                    renderList(this.value);
                });

                input.addEventListener('blur', function() {
                    setTimeout(function() { dropdown.style.display = 'none'; }, 150);
                });

                // Allow clearing
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        dropdown.style.display = 'none';
                        input.blur();
                    }
                });
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
