@extends('layouts.app')
@section('title', 'Ден ' . $day . ' — Оброци')
@section('content')

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-6">
    <div>
        <h1 class="text-2xl font-bold">Ден {{ $day }}</h1>
        <p class="text-gray-400 text-sm mt-0.5">{{ $start->format('l, d F Y') }}</p>
    </div>
    <a href="{{ route('dashboard') }}" class="text-sm text-gray-400 hover:text-loss">&larr; Назад</a>
</div>

{{-- Add meal form --}}
<div class="card p-5 mb-6">
    <p class="text-gray-400 text-xs uppercase mb-4">+ Додај оброк</p>
    <form method="POST" action="{{ route('meals.store') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="text-xs text-gray-400 mb-1 block">Назив на оброк *</label>
                <input type="text" name="name" required placeholder="пр. Овесна каша со јаткасти плодови"
                       class="w-full bg-bg border border-border rounded-xl px-4 py-2.5 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-gold">
            </div>
            <div>
                <label class="text-xs text-gray-400 mb-1 block">Време на јадење</label>
                <input type="datetime-local" name="eaten_at"
                       value="{{ $start->format('Y-m-d') }}T{{ now()->format('H:i') }}"
                       class="w-full bg-bg border border-border rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-gold">
            </div>
        </div>
        <div>
            <label class="text-xs text-gray-400 mb-1 block">Опис (незадолжително)</label>
            <textarea name="description" rows="2" placeholder="Состојки, калории, белешки..."
                      class="w-full bg-bg border border-border rounded-xl px-4 py-2.5 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-gold resize-none"></textarea>
        </div>
        <div>
            <label class="text-xs text-gray-400 mb-1 block">Фотографија (незадолжително)</label>
            <input type="file" name="photo" accept="image/*"
                   class="w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-gold/10 file:text-gold hover:file:bg-gold/20 cursor-pointer">
        </div>
        <button type="submit" class="btn btn-primary text-sm">Зачувај оброк</button>
    </form>
</div>

{{-- Meals list --}}
@if($meals->isEmpty())
    <div class="card p-8 text-center text-gray-500">
        <p class="text-2xl mb-2">🍽️</p>
        <p>Нема внесени оброци за овој ден.</p>
    </div>
@else
    <div class="space-y-6">
        @foreach($meals as $meal)
            @include('meals._meal_card', ['meal' => $meal])
        @endforeach
    </div>
@endif

<script>
    // Interactive star rating highlight
    document.querySelectorAll('[id^="stars-"]').forEach(container => {
        const labels = container.querySelectorAll('label');
        const stars = container.querySelectorAll('.star-btn');
        labels.forEach((label, idx) => {
            label.addEventListener('mouseenter', () => {
                stars.forEach((s, i) => s.classList.toggle('text-gold', i <= idx));
                stars.forEach((s, i) => s.classList.toggle('text-gray-600', i > idx));
            });
            label.addEventListener('click', () => {
                stars.forEach((s, i) => s.classList.toggle('text-gold', i <= idx));
                stars.forEach((s, i) => s.classList.toggle('text-gray-600', i > idx));
            });
        });
        container.addEventListener('mouseleave', () => {
            const checked = container.querySelector('input[type=radio]:checked');
            const val = checked ? parseInt(checked.value) : 0;
            stars.forEach((s, i) => {
                s.classList.toggle('text-gold', i < val);
                s.classList.toggle('text-gray-600', i >= val);
            });
        });
    });
</script>
@endsection
