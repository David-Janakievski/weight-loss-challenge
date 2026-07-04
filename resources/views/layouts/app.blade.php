<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Weight Loss Challenge')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        bg: '#0f1115',
                        card: '#171a21',
                        border: '#272b35',
                        gold: '#f5c542',
                        loss: '#34d399',
                        gain: '#f87171',
                    }
                }
            }
        }
    </script>
    <style>
        body { background: #0f1115; color: #f3f4f6; font-family: 'Inter', system-ui, sans-serif; }
        .card { background: #171a21; border: 1px solid #272b35; border-radius: 1rem; transition: transform .2s, box-shadow .2s; }
        .card:hover { box-shadow: 0 8px 24px rgba(0,0,0,.4); }
        .btn { border-radius: .75rem; padding: .6rem 1.2rem; font-weight: 600; transition: .15s; }
        .btn-primary { background: #34d399; color: #0f1115; }
        .btn-primary:hover { background: #2bb583; }
        .btn-gold { background: #f5c542; color: #0f1115; }
        .fade-in { animation: fadeIn .4s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(6px);} to { opacity: 1; transform: translateY(0);} }

        /* Mobile nav */
        #mobile-menu { display: none; }
        #mobile-menu.open { display: flex; }

        /* Horizontally scrollable tables on mobile */
        .table-scroll { overflow-x: auto; -webkit-overflow-scrolling: touch; }
    </style>
</head>
<body class="min-h-screen">
    @auth
    <nav class="border-b border-border bg-card/60 backdrop-blur sticky top-0 z-40">
        <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 shrink-0 group">
                <img src="{{ asset('images/logo.png') }}"
                     alt="Слабеење Лето 2026"
                     class="h-9 w-9 rounded-full object-cover ring-1 ring-border group-hover:ring-gold transition">
                <span class="font-bold text-sm leading-tight text-white hidden sm:block">
                    Слабеење Лето 2026<br>
                    <span class="text-gold font-semibold text-xs tracking-wide">KADIDERE</span>
                </span>
            </a>

            {{-- Desktop nav --}}
            <div class="hidden md:flex items-center gap-4 text-sm">
                <a href="{{ route('dashboard') }}" class="hover:text-gold">Контролна табла</a>
                <a href="{{ route('leaderboard') }}" class="hover:text-gold">Рангирање</a>
                <a href="{{ route('checkin.create') }}" class="hover:text-gold">Пријавување</a>
                @if(auth()->user()->is_admin)
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-gold">Администратор</a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-red-400 hover:text-red-300">Одјави се</button>
                </form>
            </div>

            {{-- Hamburger button (mobile only) --}}
            <button id="nav-toggle" class="md:hidden p-2 rounded-lg text-gray-400 hover:text-white hover:bg-white/10 transition" aria-label="Toggle menu">
                <svg id="icon-open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg id="icon-close" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Mobile dropdown menu --}}
        <div id="mobile-menu" class="md:hidden flex-col border-t border-border bg-card px-4 py-3 gap-1 text-sm">
            <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-lg hover:bg-white/5 hover:text-gold">Контролна табла</a>
            <a href="{{ route('leaderboard') }}" class="block px-3 py-2 rounded-lg hover:bg-white/5 hover:text-gold">Рангирање</a>
            <a href="{{ route('checkin.create') }}" class="block px-3 py-2 rounded-lg hover:bg-white/5 hover:text-gold">Пријавување</a>
            @if(auth()->user()->is_admin)
                <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-lg hover:bg-white/5 hover:text-gold">Администратор</a>
            @endif
            <form method="POST" action="{{ route('logout') }}" class="mt-1">
                @csrf
                <button class="w-full text-left px-3 py-2 rounded-lg text-red-400 hover:bg-white/5 hover:text-red-300">Одјави се</button>
            </form>
        </div>
    </nav>

    <script>
        const toggle = document.getElementById('nav-toggle');
        const menu = document.getElementById('mobile-menu');
        const iconOpen = document.getElementById('icon-open');
        const iconClose = document.getElementById('icon-close');
        toggle.addEventListener('click', () => {
            menu.classList.toggle('open');
            iconOpen.classList.toggle('hidden');
            iconClose.classList.toggle('hidden');
        });
    </script>
    @endauth

    <main class="max-w-6xl mx-auto px-4 py-6 md:py-8">
        @if(session('success'))
            <div class="mb-6 rounded-xl border border-loss/40 bg-loss/10 text-loss px-4 py-3 fade-in">
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="mb-6 rounded-xl border border-gain/40 bg-gain/10 text-gain px-4 py-3 fade-in">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
