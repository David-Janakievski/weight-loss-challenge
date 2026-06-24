@extends('layouts.app')
@section('title', 'Контролна табла')
@section('content')

@if($showReminder)
<div id="reminderBanner" class="fade-in mb-6 rounded-2xl border-2 border-gold bg-gold/10 p-5 flex items-center justify-between gap-4">
    <div>
        <p class="font-bold text-gold text-lg">📅 Потсетник за неделно пријавување</p>
        <p class="text-sm text-gray-200">Денес е вторник — време е да внесеш напредок за оваа недела. Прикачи тежина и фотографија на прогрес.</p>
    </div>
    <div class="flex items-center gap-3 shrink-0">
        <a href="{{ route('checkin.create') }}" class="btn btn-gold whitespace-nowrap">Прикачи неделно пријавување</a>
        <button onclick="document.getElementById('reminderBanner').remove()" class="text-gray-400 hover:text-white text-xl leading-none">&times;</button>
    </div>
</div>
@elseif($weekNumber > 0)
<div class="fade-in mb-6 rounded-2xl border border-loss/40 bg-loss/10 p-4 text-loss">
    ✅ Неделното пријавување е завршено за оваа недела.
</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
    <div class="card p-5">
        <p class="text-gray-400 text-xs uppercase">Почетна тежина</p>
        <p class="text-2xl font-bold mt-1">{{ number_format($user->starting_weight, 1) }} кг</p>
    </div>
    <div class="card p-5">
        <p class="text-gray-400 text-xs uppercase">Тековна тежина</p>
        <p class="text-2xl font-bold mt-1">{{ number_format($user->currentWeight(), 1) }} кг</p>
    </div>
    <div class="card p-5">
        <p class="text-gray-400 text-xs uppercase">Вкупно изгубено</p>
        <p class="text-2xl font-bold mt-1 {{ $user->weightLost() >= 0 ? 'text-loss' : 'text-gain' }}">
            {{ number_format($user->weightLost(), 1) }} кг ({{ number_format($user->percentLost(), 1) }}%)
        </p>
    </div>
    <div class="card p-5">
        <p class="text-gray-400 text-xs uppercase">Ранг</p>
        <p class="text-2xl font-bold mt-1 text-gold">#{{ $rank }} / {{ $totalParticipants }}</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
    <div class="card p-5 md:col-span-2">
        <p class="text-gray-400 text-xs uppercase mb-3">Напредок на тежината</p>
        <canvas id="weightChart" height="120"></canvas>
    </div>
    <div class="card p-5 flex flex-col items-center justify-center">
        <p class="text-gray-400 text-xs uppercase mb-3 self-start">Последна фотографија</p>
        @if($latestPhoto)
            <img src="{{ Storage::url($latestPhoto) }}" class="rounded-xl max-h-48 object-cover">
        @else
            <p class="text-gray-500 text-sm">Сè уште нема фотографија</p>
        @endif
    </div>
</div>

<div class="card p-5">
    <p class="text-gray-400 text-xs uppercase mb-2">Денови до 1 август</p>
    <p class="text-4xl font-bold text-gold">{{ $daysRemaining }}</p>
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
