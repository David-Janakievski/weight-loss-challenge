@extends('layouts.app')
@section('title', 'Рангирање')
@section('content')

<h1 class="text-2xl font-bold mb-6">🏆 Рангирање</h1>

@if($biggestLoserOfWeek)
<div class="mb-6 card p-5 border-gold/50 fade-in">
    <p class="text-gold font-semibold">🔥 Најголем губитник на тежина за оваа недела</p>
    <p class="text-lg mt-1">{{ $biggestLoserOfWeek['user']->name }} — {{ number_format($biggestLoserOfWeek['lost'], 1) }} кг оваа недела</p>
</div>
@endif

<div class="card overflow-hidden fade-in">
    <table class="w-full text-sm">
        <thead class="bg-bg/60 text-gray-400 uppercase text-xs">
            <tr>
                <th class="text-left px-4 py-3">Ранг</th>
                <th class="text-left px-4 py-3">Име</th>
                <th class="text-right px-4 py-3">Почетна</th>
                <th class="text-right px-4 py-3">Тековна</th>
                <th class="text-right px-4 py-3">Изгубено</th>
                <th class="text-right px-4 py-3">% Изгубено</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ranked as $i => $u)
                @php
                    $rank = $i + 1;
                    $medal = $rank === 1 ? '🥇' : ($rank === 2 ? '🥈' : ($rank === 3 ? '🥉' : null));
                @endphp
                <tr class="border-t border-border hover:bg-bg/40 transition">
                    <td class="px-4 py-3 font-bold {{ $rank <= 3 ? 'text-gold' : '' }}">
                        {{ $medal ?? $rank }}
                    </td>
                    <td class="px-4 py-3">
                        <a href="{{ route('profile.show', $u) }}" class="hover:text-loss">{{ $u->name }}</a>
                    </td>
                    <td class="px-4 py-3 text-right">{{ number_format($u->starting_weight, 1) }} кг</td>
                    <td class="px-4 py-3 text-right">{{ number_format($u->currentWeight(), 1) }} кг</td>
                    <td class="px-4 py-3 text-right {{ $u->weightLost() >= 0 ? 'text-loss' : 'text-gain' }}">
                        {{ number_format($u->weightLost(), 1) }} кг
                    </td>
                    <td class="px-4 py-3 text-right {{ $u->percentLost() >= 0 ? 'text-loss' : 'text-gain' }}">
                        {{ number_format($u->percentLost(), 1) }}%
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
