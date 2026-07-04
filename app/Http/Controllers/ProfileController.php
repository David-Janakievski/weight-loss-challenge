<?php

namespace App\Http\Controllers;

use App\Models\User;

class ProfileController extends Controller
{
    public function show(User $user)
    {
        $user->load('checkins');

        $chartLabels = $user->checkins->map(fn ($c) => 'Седмица ' . $c->week_number)->prepend('Почеток');
        $chartData = $user->checkins->map(fn ($c) => (float) $c->weight)->prepend((float) $user->starting_weight);

        $meals = $user->meals()
            ->with('comments.user')
            ->orderByDesc('eaten_at')
            ->get();

        return view('profile.show', [
            'profileUser' => $user,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
            'meals' => $meals,
        ]);
    }
}
