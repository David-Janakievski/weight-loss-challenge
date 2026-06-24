@extends('layouts.app')
@section('title', $profileUser->name)
@section('content')

<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold">{{ $profileUser->name }}</h1>
    <a href="{{ route('leaderboard') }}" class="text-sm text-gray-400 hover:text-loss">&larr; Назад кон рангирање</a>
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
    <div class="card p-5">
        <p class="text-gray-400 text-xs uppercase">Почетна тежина</p>
        <p class="text-2xl font-bold mt-1">{{ number_format($profileUser->starting_weight, 1) }} кг</p>
    </div>
    <div class="card p-5">
        <p class="text-gray-400 text-xs uppercase">Тековна тежина</p>
        <p class="text-2xl font-bold mt-1">{{ number_format($profileUser->currentWeight(), 1) }} кг</p>
    </div>
    <div class="card p-5">
        <p class="text-gray-400 text-xs uppercase">Вкупно изгубено</p>
        <p class="text-2xl font-bold mt-1 {{ $profileUser->weightLost() >= 0 ? 'text-loss' : 'text-gain' }}">
            {{ number_format($profileUser->weightLost(), 1) }} кг
        </p>
    </div>
    <div class="card p-5">
        <p class="text-gray-400 text-xs uppercase">% Изгубено</p>
        <p class="text-2xl font-bold mt-1 {{ $profileUser->percentLost() >= 0 ? 'text-loss' : 'text-gain' }}">
            {{ number_format($profileUser->percentLost(), 1) }}%
        </p>
    </div>
</div>

@if($profileUser->goal_note)
<div class="card p-4 mb-8 text-sm text-gray-300">🎯 Цел: {{ $profileUser->goal_note }}</div>
@endif

<div class="card p-5 mb-8">
    <p class="text-gray-400 text-xs uppercase mb-3">Напредок на тежината</p>
    <canvas id="weightChart" height="100"></canvas>
</div>

<div class="card p-5">
    <p class="text-gray-400 text-xs uppercase mb-4">Неделна галерија со фотографии</p>
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        @if($profileUser->starting_photo)
        <div class="text-center">
            <img src="{{ Storage::url($profileUser->starting_photo) }}" class="rounded-lg w-full h-32 object-cover">
            <p class="text-xs text-gray-500 mt-1">Почеток</p>
        </div>
        @endif
        @foreach($profileUser->checkins as $c)
        <div class="text-center">
            <img src="{{ Storage::url($c->photo) }}" class="rounded-lg w-full h-32 object-cover">
            <p class="text-xs text-gray-500 mt-1">Седмица {{ $c->week_number }}</p>
        </div>
        @endforeach
    </div>
</div>

<script>
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
            plugins: { legend: { labels: { color: '#cbd5e1' } } },
            scales: {
                x: { ticks: { color: '#94a3b8' }, grid: { color: '#272b35' } },
                y: { ticks: { color: '#94a3b8' }, grid: { color: '#272b35' } },
            }
        }
    });
</script>
@endsection
