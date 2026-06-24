@extends('layouts.app')
@section('title', 'Најава')
@section('content')
<div class="min-h-[70vh] flex items-center justify-center">
    <div class="card w-full max-w-sm p-8 fade-in">
        <h1 class="text-2xl font-bold text-center mb-1">🏆 Најава за предизвикот</h1>
        <p class="text-center text-gray-400 text-sm mb-6">Приватна апликација за следење на предизвик за слабеење</p>

        @if($errors->any())
            <div class="mb-4 rounded-lg border border-gain/40 bg-gain/10 text-gain px-3 py-2 text-sm">
                @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login.submit') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm mb-1 text-gray-400">Корисничко име</label>
                <input type="text" name="username" value="{{ old('username') }}" required autofocus
                       class="w-full rounded-lg bg-bg border border-border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-loss">
            </div>
            <div>
                <label class="block text-sm mb-1 text-gray-400">Лозинка</label>
                <input type="password" name="password" required
                       class="w-full rounded-lg bg-bg border border-border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-loss">
            </div>
            <button type="submit" class="btn btn-primary w-full">Најави се</button>
        </form>
    </div>
</div>
@endsection
