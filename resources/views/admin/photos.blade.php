@extends('layouts.app')
@section('title', 'Сите фотографии')
@section('content')

<h1 class="text-2xl font-bold mb-6">📸 Сите фотографии од прогрес</h1>

<div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-4">
    @foreach($checkins as $c)
    <div class="card p-2 text-center">
        <img src="{{ Storage::url($c->photo) }}" class="rounded-lg w-full h-32 object-cover mb-2">
        <p class="text-xs text-gray-400">{{ $c->user->name }}</p>
        <p class="text-xs text-gray-500">Седмица {{ $c->week_number }} · {{ number_format($c->weight,1) }} кг</p>
    </div>
    @endforeach
</div>
@endsection
