@extends('layouts.app')
@section('title', 'Уреди пријавување')
@section('content')
<div class="max-w-lg mx-auto card p-8 fade-in">
    <h1 class="text-xl font-bold mb-1">Уреди пријавување</h1>
    <p class="text-gray-400 text-sm mb-6">{{ $checkin->user->name }} — Седмица {{ $checkin->week_number }}</p>

    <img src="{{ Storage::url($checkin->photo) }}" class="rounded-lg max-h-48 mb-4">

    <form method="POST" action="{{ route('admin.checkins.update', $checkin) }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm mb-1 text-gray-400">Тежина (кг)</label>
            <input type="number" step="0.1" name="weight" value="{{ $checkin->weight }}" required
                   class="w-full rounded-lg bg-bg border border-border px-3 py-2">
        </div>
        <div>
            <label class="block text-sm mb-1 text-gray-400">Замени фотографија</label>
            <input type="file" name="photo" accept="image/*"
                   class="w-full rounded-lg bg-bg border border-border px-3 py-2">
        </div>
        <div>
            <label class="block text-sm mb-1 text-gray-400">Белешка</label>
            <input type="text" name="note" value="{{ $checkin->note }}" maxlength="255"
                   class="w-full rounded-lg bg-bg border border-border px-3 py-2">
        </div>
        <button type="submit" class="btn btn-primary w-full">Зачувај промени</button>
    </form>
</div>
@endsection
