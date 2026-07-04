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
        <div class="card p-5">
            {{-- Meal header --}}
            <div class="flex items-start justify-between gap-3 mb-3">
                <div class="flex items-center gap-3">
                    <div class="bg-gold/10 rounded-xl p-2 shrink-0">
                        <span class="text-gold text-lg">🍴</span>
                    </div>
                    <div>
                        <p class="font-semibold text-white">{{ $meal->name }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $meal->eaten_at->format('H:i') }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    {{-- Average rating --}}
                    @if($meal->averageRating())
                        <div class="flex items-center gap-1">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="text-sm {{ $i <= round($meal->averageRating()) ? 'text-gold' : 'text-gray-600' }}">★</span>
                            @endfor
                            <span class="text-xs text-gray-400 ml-1">{{ $meal->averageRating() }}</span>
                        </div>
                    @endif
                    {{-- Delete own meal --}}
                    @if($meal->user_id === auth()->id() || auth()->user()->is_admin)
                        <form method="POST" action="{{ route('meals.destroy', $meal) }}" onsubmit="return confirm('Избриши го овој оброк?')">
                            @csrf @method('DELETE')
                            <button class="text-xs text-red-400 hover:text-red-300">Избриши</button>
                        </form>
                    @endif
                </div>
            </div>

            {{-- Description --}}
            @if($meal->description)
                <p class="text-sm text-gray-300 mb-3 leading-relaxed">{{ $meal->description }}</p>
            @endif

            {{-- Photo --}}
            @if($meal->photo)
                <img src="{{ Storage::url($meal->photo) }}"
                     class="w-full max-h-64 object-cover rounded-xl mb-4 cursor-pointer"
                     onclick="this.classList.toggle('max-h-64')">
            @endif

            {{-- Comments --}}
            <div class="border-t border-border pt-4 mt-2 space-y-3">
                @foreach($meal->comments as $comment)
                    <div class="flex items-start gap-3">
                        <div class="w-7 h-7 rounded-full bg-border flex items-center justify-center text-xs font-bold text-gray-300 shrink-0">
                            {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-0.5">
                                <span class="text-xs font-semibold text-white">{{ $comment->user->name }}</span>
                                @if($comment->rating)
                                    <span class="flex gap-0.5">
                                        @for($i = 1; $i <= 5; $i++)
                                            <span class="text-xs {{ $i <= $comment->rating ? 'text-gold' : 'text-gray-600' }}">★</span>
                                        @endfor
                                    </span>
                                @endif
                                <span class="text-xs text-gray-600">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            @if($comment->comment)
                                <p class="text-sm text-gray-300">{{ $comment->comment }}</p>
                            @endif
                        </div>
                        @if($comment->user_id === auth()->id() || auth()->user()->is_admin)
                            <form method="POST" action="{{ route('meals.comment.delete', $comment) }}">
                                @csrf @method('DELETE')
                                <button class="text-xs text-gray-600 hover:text-red-400">&times;</button>
                            </form>
                        @endif
                    </div>
                @endforeach

                {{-- Leave a comment / rating --}}
                @php $myComment = $meal->userComment(auth()->id()); @endphp
                <form method="POST" action="{{ route('meals.comment', $meal) }}" class="pt-2 border-t border-border/50">
                    @csrf
                    <div class="flex flex-col sm:flex-row gap-3 items-start">
                        {{-- Star picker --}}
                        <div class="flex gap-1 items-center" id="stars-{{ $meal->id }}">
                            @for($i = 1; $i <= 5; $i++)
                                <label class="cursor-pointer">
                                    <input type="radio" name="rating" value="{{ $i }}" class="hidden"
                                           {{ $myComment?->rating == $i ? 'checked' : '' }}>
                                    <span class="text-xl star-btn {{ $myComment && $myComment->rating >= $i ? 'text-gold' : 'text-gray-600' }} hover:text-gold transition">★</span>
                                </label>
                            @endfor
                        </div>
                        <input type="text" name="comment"
                               value="{{ $myComment?->comment }}"
                               placeholder="{{ $myComment ? 'Уреди коментар...' : 'Остави коментар...' }}"
                               class="flex-1 bg-bg border border-border rounded-xl px-3 py-2 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-gold min-w-0">
                        <button type="submit" class="btn btn-primary text-xs shrink-0">
                            {{ $myComment ? 'Ажурирај' : 'Испрати' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
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
