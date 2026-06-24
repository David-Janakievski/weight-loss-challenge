@extends('layouts.app')
@section('title', 'Администратор')
@section('content')

<h1 class="text-2xl font-bold mb-6">⚙️ Администраторски панел</h1>

<div class="flex gap-4 mb-8 text-sm">
    <a href="{{ route('admin.photos') }}" class="text-gold hover:underline">Прикажи сите фотографии</a>
    <a href="{{ route('checkin.create') }}" class="text-gold hover:underline">Прикачи пријавување (override)</a>
</div>

<div class="card overflow-hidden mb-10">
    <p class="px-4 py-3 text-gray-400 text-xs uppercase border-b border-border">Учесници</p>
    <table class="w-full text-sm">
        <thead class="bg-bg/60 text-gray-400 uppercase text-xs">
            <tr>
                <th class="text-left px-4 py-3">Име</th>
                <th class="text-left px-4 py-3">Корисничко име</th>
                <th class="text-right px-4 py-3">Почетна</th>
                <th class="text-right px-4 py-3">Тековна</th>
                <th class="text-center px-4 py-3">Завршен onboarding</th>
                <th class="text-right px-4 py-3">Акции</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $u)
            <tr class="border-t border-border">
                <td class="px-4 py-3">{{ $u->name }}</td>
                <td class="px-4 py-3 text-gray-400">{{ $u->username }}</td>
                <td class="px-4 py-3 text-right">{{ $u->starting_weight ? number_format($u->starting_weight,1).' кг' : '—' }}</td>
                <td class="px-4 py-3 text-right">{{ $u->starting_weight ? number_format($u->currentWeight(),1).' кг' : '—' }}</td>
                <td class="px-4 py-3 text-center">{{ $u->onboarding_completed ? '✅' : '⏳' }}</td>
                <td class="px-4 py-3 text-right">
                    <a href="{{ route('admin.users.edit', $u) }}" class="text-loss hover:underline">Уреди</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="card overflow-hidden">
    <p class="px-4 py-3 text-gray-400 text-xs uppercase border-b border-border">Пријавувања</p>
    <table class="w-full text-sm">
        <thead class="bg-bg/60 text-gray-400 uppercase text-xs">
            <tr>
                <th class="text-left px-4 py-3">Корисник</th>
                <th class="text-left px-4 py-3">Седмица</th>
                <th class="text-left px-4 py-3">Датум</th>
                <th class="text-right px-4 py-3">Тежина</th>
                <th class="text-right px-4 py-3">Акции</th>
            </tr>
        </thead>
        <tbody>
            @foreach($checkins as $c)
            <tr class="border-t border-border">
                <td class="px-4 py-3">{{ $c->user->name }}</td>
                <td class="px-4 py-3">{{ $c->week_number }}</td>
                <td class="px-4 py-3 text-gray-400">{{ $c->checkin_date->format('d.m.Y') }}</td>
                <td class="px-4 py-3 text-right">{{ number_format($c->weight,1) }} кг</td>
                <td class="px-4 py-3 text-right space-x-3">
                    <a href="{{ route('admin.checkins.edit', $c) }}" class="text-loss hover:underline">Уреди</a>
                    <form method="POST" action="{{ route('admin.checkins.delete', $c) }}" class="inline" onsubmit="return confirm('Избриши ова пријавување?')">
                        @csrf @method('DELETE')
                        <button class="text-gain hover:underline">Избриши</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
