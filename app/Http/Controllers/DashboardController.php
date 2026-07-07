<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Support\Challenge;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = \App\Models\User::with('checkins')->find(Auth::id());

        $allUsers = User::where('onboarding_completed', true)->get();
        $ranked = $allUsers->sortByDesc(fn ($u) => [$u->weightLost(), $u->percentLost()])->values();
        $rank = $ranked->search(fn ($u) => $u->id === $user->id) + 1;

        $weekNumber = Challenge::currentWeekNumber();
        $isTuesday = Challenge::isTuesday();
        $hasCheckedInThisWeek = $weekNumber > 0 ? $user->hasCheckedInForWeek($weekNumber) : true;
        $showReminder = $isTuesday && $weekNumber > 0 && !$hasCheckedInThisWeek;

        $chartLabels = $user->checkins->map(fn ($c) => 'Седмица ' . $c->week_number)->prepend('Почеток');
        $chartData = $user->checkins->map(fn ($c) => (float) $c->weight)->prepend((float) $user->starting_weight);

        // Build day tiles: Day 1 ... today's day number
        $challengeStart = \App\Support\Challenge::startDate();
        $todayDay = max(1, (int) $challengeStart->startOfDay()->diffInDays(now()->startOfDay()) + 1);
        $totalDays = max(1, (int) $challengeStart->startOfDay()->diffInDays(\App\Support\Challenge::endDate()->startOfDay()) + 1);
        $daysToShow = min($todayDay, $totalDays);

        // Count meals per day for the logged-in user
        $mealCountsByDay = \App\Models\Meal::where('user_id', $user->id)
            ->selectRaw("DATE(eaten_at) as meal_date, COUNT(*) as cnt")
            ->groupBy('meal_date')
            ->get()
            ->keyBy(fn($r) => \Carbon\Carbon::parse($r->meal_date)->format('Y-m-d'));

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
            'daysToShow' => $daysToShow,
            'challengeStart' => $challengeStart,
            'mealCountsByDay' => $mealCountsByDay,
        ]);
    }
}