@extends('layouts.app')
@section('title', 'Уреди корисник')
@section('content')
<div class="max-w-lg mx-auto card p-8 fade-in">
    <h1 class="text-xl font-bold mb-6">Уреди {{ $user->name }}</h1>
    <form method="POST" action="{{ route('admin.users.update', $user) }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm mb-1 text-gray-400">Име</label>
            <input type="text" name="name" value="{{ $user->name }}" required
                   class="w-full rounded-lg bg-bg border border-border px-3 py-2">
        </div>
        <div>
            <label class="block text-sm mb-1 text-gray-400">Почетна тежина (кг)</label>
            <input type="number" step="0.1" name="starting_weight" value="{{ $user->starting_weight }}"
                   class="w-full rounded-lg bg-bg border border-border px-3 py-2">
        </div>
        <div>
            <label class="block text-sm mb-1 text-gray-400">Почетна фотографија (замени)</label>
            <input type="file" name="starting_photo" accept="image/*"
                   class="w-full rounded-lg bg-bg border border-border px-3 py-2">
        </div>
        <div>
            <label class="block text-sm mb-1 text-gray-400">Цел</label>
            <input type="text" name="goal_note" value="{{ $user->goal_note }}" maxlength="255"
                   class="w-full rounded-lg bg-bg border border-border px-3 py-2">
        </div>
        <label class="flex items-center gap-2 text-sm text-gold">
            <input type="checkbox" name="reset_password" value="1"> Ресетирај лозинка на стандардна (123456) и прими промена
        </label>
        <button type="submit" class="btn btn-primary w-full">Зачувај промени</button>
    </form>
</div>
@endsection
