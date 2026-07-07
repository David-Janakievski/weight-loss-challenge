<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Support\Challenge;

class LeaderboardController extends Controller
{
    public function index()
    {
        $users = User::where('onboarding_completed', true)->where('is_admin', false)->with('checkins')->get();

        $ranked = $users->sortByDesc(fn ($u) => [$u->weightLost(), $u->percentLost()])->values();

        $isEnded = Challenge::isEnded();
        $winner = $isEnded ? $ranked->first() : null;
        $loser = $isEnded ? $ranked->last() : null;

        $weekNumber = Challenge::currentWeekNumber();
        $biggestLoserOfWeek = User::where('onboarding_completed', true)
            ->where('is_admin', false)
            ->with('checkins')
            ->get()
            ->map(function ($u) use ($weekNumber) {
                $checkin = $u->checkins->firstWhere('week_number', $weekNumber);
                $prev = $u->checkins->firstWhere('week_number', $weekNumber - 1);
                $prevWeight = $prev ? (float) $prev->weight : (float) $u->starting_weight;
                $lost = $checkin ? round($prevWeight - (float) $checkin->weight, 2) : null;
                return ['user' => $u, 'lost' => $lost];
            })
            ->filter(fn ($row) => $row['lost'] !== null)
            ->sortByDesc('lost')
            ->first();

        return view('leaderboard.index', [
            'ranked' => $ranked,
            'biggestLoserOfWeek' => $biggestLoserOfWeek,
            'isEnded' => $isEnded,
            'winner' => $winner,
            'loser' => $loser,
        ]);
    }
}