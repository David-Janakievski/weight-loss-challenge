@extends('layouts.app')
@section('title', $isEnded ? 'Финални резултати' : 'Рангирање')
@section('content')

    @if ($isEnded)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
            <div class="card p-8 text-center border-gold border-2 fade-in">
                <p class="text-5xl mb-3">🏆</p>
                <p class="text-gold text-sm uppercase tracking-wide mb-1">Победник на предизвикот</p>
                <h1 class="text-2xl font-bold mb-2">{{ $winner->name }}</h1>
                <p class="text-gray-300">
                    {{ number_format($winner->weightLost(), 1) }} кг изгубени
                    ({{ number_format($winner->percentLost(), 1) }}%)
                </p>
            </div>

            <div class="card p-8 text-center border-gain/60 border-2 fade-in">
                <p class="text-5xl mb-3">🍩</p>
                <p class="text-gain text-sm uppercase tracking-wide mb-1">Последно место</p>
                <h1 class="text-2xl font-bold mb-2">{{ $loser->name }}</h1>
                <p class="text-gray-300">
                    {{ number_format($loser->weightLost(), 1) }} кг изгубени
                    ({{ number_format($loser->percentLost(), 1) }}%)
                </p>
            </div>
        </div>

        <h2 class="text-xl font-bold mb-4">📋 Финални резултати на сите учесници</h2>
    @else
        <h1 class="text-2xl font-bold mb-6">🏆 Рангирање</h1>

        @if ($biggestLoserOfWeek)
            <div class="mb-6 card p-5 border-gold/50 fade-in">
                <p class="text-gold font-semibold">🔥 Најголем губитник на тежина за оваа недела</p>
                <p class="text-lg mt-1">{{ $biggestLoserOfWeek['user']->name }} —
                    {{ number_format($biggestLoserOfWeek['lost'], 1) }} кг оваа недела</p>
            </div>
        @endif
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
                @foreach ($ranked as $i => $u)
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
