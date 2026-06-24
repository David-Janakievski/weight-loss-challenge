<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Support\Challenge;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load('checkins');

        $allUsers = User::where('onboarding_completed', true)->get();
        $ranked = $allUsers->sortByDesc(fn ($u) => [$u->weightLost(), $u->percentLost()])->values();
        $rank = $ranked->search(fn ($u) => $u->id === $user->id) + 1;

        $weekNumber = Challenge::currentWeekNumber();
        $isTuesday = Challenge::isTuesday();
        $hasCheckedInThisWeek = $weekNumber > 0 ? $user->hasCheckedInForWeek($weekNumber) : true;
        $showReminder = $isTuesday && $weekNumber > 0 && !$hasCheckedInThisWeek;

        $chartLabels = $user->checkins->map(fn ($c) => 'Седмица ' . $c->week_number)->prepend('Почеток');
        $chartData = $user->checkins->map(fn ($c) => (float) $c->weight)->prepend((float) $user->starting_weight);

        return view('dashboard.index', [
            'user' => $user,
            'rank' => $rank,
            'totalParticipants' => $allUsers->count(),
            'daysRemaining' => Challenge::daysRemaining(),
            'showReminder' => $showReminder,
            'weekNumber' => $weekNumber,
            'latestPhoto' => optional($user->latestCheckin())->photo ?? $user->starting_photo,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
        ]);
    }
}
