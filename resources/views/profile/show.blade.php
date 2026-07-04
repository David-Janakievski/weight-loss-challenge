@extends('layouts.app')
@section('title', $profileUser->name)
@section('content')

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-6">
        <h1 class="text-2xl font-bold">{{ $profileUser->name }}</h1>
        <a href="{{ route('leaderboard') }}" class="text-sm text-gray-400 hover:text-loss">&larr; Назад кон рангирање</a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6 md:mb-8">
        <div class="card p-4 sm:p-5">
            <p class="text-gray-400 text-xs uppercase">Почетна тежина</p>
            <p class="text-xl sm:text-2xl font-bold mt-1">{{ number_format($profileUser->starting_weight, 1) }} кг</p>
        </div>
        <div class="card p-4 sm:p-5">
            <p class="text-gray-400 text-xs uppercase">Тековна тежина</p>
            <p class="text-xl sm:text-2xl font-bold mt-1">{{ number_format($profileUser->currentWeight(), 1) }} кг</p>
        </div>
        <div class="card p-4 sm:p-5">
            <p class="text-gray-400 text-xs uppercase">Вкупно изгубено</p>
            <p class="text-xl sm:text-2xl font-bold mt-1 {{ $profileUser->weightLost() >= 0 ? 'text-loss' : 'text-gain' }}">
                {{ number_format($profileUser->weightLost(), 1) }} кг
            </p>
        </div>
        <div class="card p-4 sm:p-5">
            <p class="text-gray-400 text-xs uppercase">% Изгубено</p>
            <p
                class="text-xl sm:text-2xl font-bold mt-1 {{ $profileUser->percentLost() >= 0 ? 'text-loss' : 'text-gain' }}">
                {{ number_format($profileUser->percentLost(), 1) }}%
            </p>
        </div>
    </div>

    @if ($profileUser->goal_note)
        <div class="card p-4 mb-6 md:mb-8 text-sm text-gray-300">🎯 Цел: {{ $profileUser->goal_note }}</div>
    @endif

    <div class="card p-5 mb-6 md:mb-8">
        <p class="text-gray-400 text-xs uppercase mb-3">Напредок на тежината</p>
        <canvas id="weightChart" height="100"></canvas>
    </div>

    {{-- Photo gallery --}}
    <div class="card p-5">
        <p class="text-gray-400 text-xs uppercase mb-4">Неделна галерија со фотографии</p>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">

            @if ($profileUser->starting_photo)
                <div class="text-center cursor-pointer group" onclick="openLightbox(0)">
                    <div class="overflow-hidden rounded-lg">
                        <img src="{{ Storage::url($profileUser->starting_photo) }}"
                            class="w-full h-28 sm:h-32 object-cover transition duration-200 group-hover:scale-105 group-hover:brightness-110">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Почеток</p>
                </div>
            @endif

            @foreach ($profileUser->checkins as $i => $c)
                <div class="text-center cursor-pointer group"
                    onclick="openLightbox({{ $profileUser->starting_photo ? $i + 1 : $i }})">
                    <div class="overflow-hidden rounded-lg">
                        <img src="{{ Storage::url($c->photo) }}"
                            class="w-full h-28 sm:h-32 object-cover transition duration-200 group-hover:scale-105 group-hover:brightness-110">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Седмица {{ $c->week_number }}</p>
                </div>
            @endforeach

        </div>
    </div>

    {{-- Lightbox --}}
    <div id="lightbox" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4"
        style="background:rgba(0,0,0,0.92)">
        <div class="relative max-w-2xl w-full">

            {{-- Close --}}
            <button onclick="closeLightbox()"
                class="absolute -top-10 right-0 text-gray-400 hover:text-white text-3xl leading-none">&times;</button>

            {{-- Image --}}
            <img id="lightbox-img" src="" class="w-full max-h-[75vh] object-contain rounded-xl">

            {{-- Label --}}
            <p id="lightbox-label" class="text-center text-sm text-gray-400 mt-3"></p>

            {{-- Prev / Next --}}
            <div class="flex justify-between mt-4">
                <button onclick="prevPhoto()"
                    class="px-4 py-2 rounded-lg bg-white/10 hover:bg-white/20 text-white text-sm transition">←
                    Претходна</button>
                <span id="lightbox-counter" class="text-gray-500 text-sm self-center"></span>
                <button onclick="nextPhoto()"
                    class="px-4 py-2 rounded-lg bg-white/10 hover:bg-white/20 text-white text-sm transition">Следна
                    →</button>
            </div>
        </div>
    </div>

    <script>
        // Build photos array from Blade
        const photos = [
            @if ($profileUser->starting_photo)
                {
                    src: "{{ Storage::url($profileUser->starting_photo) }}",
                    label: "Почеток"
                },
            @endif
            @foreach ($profileUser->checkins as $c)
                {
                    src: "{{ Storage::url($c->photo) }}",
                    label: "Седмица {{ $c->week_number }}"
                },
            @endforeach
        ];

        let current = 0;

        function openLightbox(index) {
            current = index;
            document.getElementById('lightbox').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            renderLightbox();
        }

        function closeLightbox() {
            document.getElementById('lightbox').classList.add('hidden');
            document.body.style.overflow = '';
        }

        function renderLightbox() {
            document.getElementById('lightbox-img').src = photos[current].src;
            document.getElementById('lightbox-label').textContent = photos[current].label;
            document.getElementById('lightbox-counter').textContent = (current + 1) + ' / ' + photos.length;
        }

        function prevPhoto() {
            current = (current - 1 + photos.length) % photos.length;
            renderLightbox();
        }

        function nextPhoto() {
            current = (current + 1) % photos.length;
            renderLightbox();
        }

        // Close on background click
        document.getElementById('lightbox').addEventListener('click', function(e) {
            if (e.target === this) closeLightbox();
        });

        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (document.getElementById('lightbox').classList.contains('hidden')) return;
            if (e.key === 'ArrowRight') nextPhoto();
            if (e.key === 'ArrowLeft') prevPhoto();
            if (e.key === 'Escape') closeLightbox();
        });

        // Weight chart
        new Chart(document.getElementById('weightChart'), {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Тежина (кг)',
                    data: @json($chartData),
                    borderColor: '#34d399',
                    backgroundColor: 'rgba(52,211,153,0.15)',
                    fill: true,
                    tension: 0.35,
                }]
            },
            options: {
                plugins: {
                    legend: {
                        labels: {
                            color: '#cbd5e1'
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            color: '#94a3b8'
                        },
                        grid: {
                            color: '#272b35'
                        }
                    },
                    y: {
                        ticks: {
                            color: '#94a3b8'
                        },
                        grid: {
                            color: '#272b35'
                        }
                    },
                }
            }
        });
    </script>
@endsection
