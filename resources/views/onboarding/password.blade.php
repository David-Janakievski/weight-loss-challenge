@extends('layouts.app')
@section('title', 'Нова лозинка')
@section('content')
<div class="min-h-[70vh] flex items-center justify-center">
    <div class="card w-full max-w-sm p-8 fade-in">
        <h1 class="text-xl font-bold mb-1">Чекор 1 од 2: Постави нова лозинка</h1>
        <p class="text-gray-400 text-sm mb-6">Сè уште користите стандардна лозинка. Изберете нова за да продолжите.</p>

        <form method="POST" action="{{ route('onboarding.password.submit') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm mb-1 text-gray-400">Нова лозинка</label>
                <input type="password" name="password" required minlength="6"
                       class="w-full rounded-lg bg-bg border border-border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-loss">
            </div>
            <div>
                <label class="block text-sm mb-1 text-gray-400">Потврди лозинка</label>
                <input type="password" name="password_confirmation" required minlength="6"
                       class="w-full rounded-lg bg-bg border border-border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-loss">
            </div>
            <button type="submit" class="btn btn-primary w-full">Продолжи</button>
        </form>
    </div>
</div>
@endsection
