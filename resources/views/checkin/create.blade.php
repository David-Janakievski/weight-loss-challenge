@extends('layouts.app')
@section('title', 'Неделно пријавување')
@section('content')
<div class="max-w-lg mx-auto card p-8 fade-in">
    <h1 class="text-xl font-bold mb-1">Неделно пријавување — Седмица {{ $weekNumber > 0 ? $weekNumber : '—' }}</h1>

    @if(!$canCheckIn && !auth()->user()->is_admin)
        <p class="text-gray-400 text-sm mb-6">Пријавувањето е отворено секој вторник. Врати се тогаш, или провери го рангирањето засега.</p>
        <a href="{{ route('leaderboard') }}" class="btn btn-primary inline-block">Погледни рангирање</a>
    @else
        <p class="text-gray-400 text-sm mb-6">Внеси тежина за оваа недела и фотографија на прогрес.</p>
        <form method="POST" action="{{ route('checkin.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm mb-1 text-gray-400">Тековна тежина (кг)</label>
                <input type="number" step="0.1" name="weight" required
                       class="w-full rounded-lg bg-bg border border-border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-loss">
            </div>
            <div>
                <label class="block text-sm mb-1 text-gray-400">Фотографија на прогрес</label>
                <input type="file" name="photo" accept="image/*" required
                       class="w-full rounded-lg bg-bg border border-border px-3 py-2">
            </div>
            <div>
                <label class="block text-sm mb-1 text-gray-400">Белешка (опционално)</label>
                <input type="text" name="note" maxlength="255"
                       class="w-full rounded-lg bg-bg border border-border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-loss">
            </div>

            @if(auth()->user()->is_admin)
            <label class="flex items-center gap-2 text-sm text-gold">
                <input type="checkbox" name="override" value="1"> Администраторско override (игнорирај правила за вторник/дупликат)
            </label>
            @endif

            <button type="submit" class="btn btn-primary w-full">Прикачи пријавување</button>
        </form>
    @endif
</div>
@endsection
