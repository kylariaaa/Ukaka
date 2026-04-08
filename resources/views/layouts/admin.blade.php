<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin Dashboard' }} - HoobyShoop</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Wrapper: fills viewport, sidebar + main side-by-side */
        #layout-wrapper {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        /* ── SIDEBAR (locked, never scrolls with page) ──── */
        #admin-sidebar {
            width: 256px;
            flex-shrink: 0;
            height: 100vh;
            background: #4A0505;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 3rem 1.5rem;
            position: relative;
            z-index: 40;
            overflow-y: auto;
            transition: transform 0.3s ease-in-out;
        }

        /* ── MAIN CONTENT (this is the ONLY area that scrolls) */
        #admin-main {
            flex: 1;
            height: 100vh;
            padding: 2.5rem;
            overflow-y: auto;
            overflow-x: auto;
            min-width: 0;
        }

        /* ── MOBILE (< 768 px) ─────────────────────────────── */
        @media (max-width: 767px) {
            #layout-wrapper {
                display: block;
                /* single-column on mobile */
            }

            #admin-sidebar {
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                transform: translateX(-100%);
                z-index: 40;
            }

            #admin-sidebar.open {
                transform: translateX(0);
            }

            #admin-main {
                padding: 1.25rem;
                padding-top: 4.5rem;
                /* room for hamburger button */
            }

            #hamburger-btn {
                display: flex !important;
            }
        }

        #hamburger-btn {
            display: none;
        }

        #sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .5);
            z-index: 30;
        }

        #sidebar-overlay.visible {
            display: block;
        }
    </style>
</head>

<body class="bg-[#F5F5F5] font-inter">

    {{-- Overlay (mobile only) --}}
    <div id="sidebar-overlay" onclick="closeSidebar()"></div>

    {{-- Hamburger (mobile only, hidden by CSS on desktop) --}}
    <button id="hamburger-btn" onclick="toggleSidebar()" style="display:none; position:fixed; top:1rem; left:1rem; z-index:50;
                   background:#4A0505; color:#fff; border:none; border-radius:0.75rem;
                   padding:0.625rem; cursor:pointer; align-items:center; justify-content:center;"
        aria-label="Buka menu">
        <svg id="ham-icon" style="width:1.5rem;height:1.5rem;" fill="none" stroke="currentColor" stroke-width="2.5"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
        <svg id="close-icon" style="width:1.5rem;height:1.5rem;display:none;" fill="none" stroke="currentColor"
            stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>

    <div id="layout-wrapper">

        {{-- ===== SIDEBAR ===== --}}
        <aside id="admin-sidebar">

            <div style="display:flex;flex-direction:column;gap:4rem;">
                {{-- Logo --}}
                <div style="text-align:center;margin-top:2rem;">
                    <a href="{{ route('admin.dashboard') }}"
                        style="color:#fff;font-size:1.875rem;font-weight:900;line-height:1.2;letter-spacing:0.05em;text-decoration:none;display:block;">
                        Hooby<br>Shoop
                    </a>
                </div>

                {{-- Nav Links --}}
                <nav style="display:flex;flex-direction:column;gap:1.5rem;padding-left:0.5rem;">
                    <a href="{{ route('admin.products.create') }}" onclick="closeSidebar()" style="display:flex;align-items:center;justify-content:space-between;text-decoration:none;
                              color:{{ request()->routeIs('admin.products.create') ? '#fff' : '#E8E8E8' }};
                              font-weight:{{ request()->routeIs('admin.products.create') ? '700' : '500' }};">
                        <span style="font-size:1.125rem;">add product</span>
                        <img src="{{ asset('images/add-icon.png') }}" alt="Add"
                            style="width:1.25rem;height:1.25rem;opacity:.8;">
                    </a>

                    <a href="{{ route('admin.products.index') }}" onclick="closeSidebar()"
                        style="display:flex;align-items:center;justify-content:space-between;text-decoration:none;
                              color:{{ (request()->routeIs('admin.products.index') || request()->routeIs('admin.products.edit')) ? '#fff' : '#E8E8E8' }};
                              font-weight:{{ (request()->routeIs('admin.products.index') || request()->routeIs('admin.products.edit')) ? '700' : '500' }};">
                        <span style="font-size:1.125rem;">List product</span>
                        <img src="{{ asset('images/list-icon.png') }}" alt="List"
                            style="width:1.25rem;height:1.25rem;opacity:.8;">
                    </a>

                    <a href="{{ route('admin.orders.index') }}" onclick="closeSidebar()" style="display:flex;align-items:center;justify-content:space-between;text-decoration:none;
                              color:{{ request()->routeIs('admin.orders.index') ? '#fff' : '#E8E8E8' }};
                              font-weight:{{ request()->routeIs('admin.orders.index') ? '700' : '500' }};">
                        <span style="font-size:1.125rem;">Products ordered</span>
                        <img src="{{ asset('images/order-icon.png') }}" alt="Orders"
                            style="width:1.25rem;height:1.25rem;opacity:.8;">
                    </a>
                </nav>
            </div>

            {{-- Logout --}}
            <div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" style="width:100%;background:#FF0000;color:#fff;font-weight:700;font-size:1.125rem;
                                   padding:0.75rem;border-radius:0.75rem;border:none;cursor:pointer;">
                        LOGOUT
                    </button>
                </form>
            </div>
        </aside>

        {{-- ===== MAIN CONTENT ===== --}}
        <main id="admin-main">

            {{-- Flash Messages --}}
            @if(session('success'))
            <div
                style="background:#dcfce7;border:1px solid #86efac;color:#166534;padding:0.75rem 1rem;border-radius:0.5rem;margin-bottom:1.5rem;">
                {{ session('success') }}
            </div>
            @endif

            {{ $slot }}
        </main>

    </div>{{-- /layout-wrapper --}}

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('admin-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const hamIcon = document.getElementById('ham-icon');
            const closeIcon = document.getElementById('close-icon');
            const isOpen = sidebar.classList.contains('open');

            if (isOpen) {
                sidebar.classList.remove('open');
                overlay.classList.remove('visible');
                hamIcon.style.display = 'block';
                closeIcon.style.display = 'none';
            } else {
                sidebar.classList.add('open');
                overlay.classList.add('visible');
                hamIcon.style.display = 'none';
                closeIcon.style.display = 'block';
            }
        }

        function closeSidebar() {
            const sidebar = document.getElementById('admin-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const hamIcon = document.getElementById('ham-icon');
            const closeIcon = document.getElementById('close-icon');
            sidebar.classList.remove('open');
            overlay.classList.remove('visible');
            hamIcon.style.display = 'block';
            closeIcon.style.display = 'none';
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeSidebar();
        });
    </script>
</body>

</html>