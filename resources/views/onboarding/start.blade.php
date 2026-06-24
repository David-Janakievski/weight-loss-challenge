@extends('layouts.app')
@section('title', 'Почетни податоци')
@section('content')
<div class="min-h-[70vh] flex items-center justify-center">
    <div class="card w-full max-w-md p-8 fade-in">
        <h1 class="text-xl font-bold mb-1">Чекор 2 од 2: Твои почетни податоци</h1>
        <p class="text-gray-400 text-sm mb-6">Ова е твојата основна точка. Може да се внесе само еднаш — избери внимателно!</p>

        <form method="POST" action="{{ route('onboarding.start.submit') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm mb-1 text-gray-400">Почетна тежина (кг)</label>
                <input type="number" step="0.1" name="starting_weight" required
                       class="w-full rounded-lg bg-bg border border-border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-loss">
            </div>
            <div>
                <label class="block text-sm mb-1 text-gray-400">Почетна фотографија</label>
                <input type="file" name="starting_photo" accept="image/*" required id="photoInput"
                       class="w-full rounded-lg bg-bg border border-border px-3 py-2">
                <img id="preview" class="mt-3 rounded-lg max-h-48 hidden">
            </div>
            <div>
                <label class="block text-sm mb-1 text-gray-400">Цел (опционално)</label>
                <input type="text" name="goal_note" maxlength="255" placeholder="напр. Да изгубам 5кг до август"
                       class="w-full rounded-lg bg-bg border border-border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-loss">
            </div>
            <button type="submit" class="btn btn-primary w-full">Започни предизвик</button>
        </form>
    </div>
</div>
<script>
    document.getElementById('photoInput').addEventListener('change', function (e) {
        const file = e.target.files[0];
        const preview = document.getElementById('preview');
        if (file) {
            preview.src = URL.createObjectURL(file);
            preview.classList.remove('hidden');
        }
    });
</script>
@endsection
