<?php

namespace App\Http\Controllers;

use App\Models\Checkin;
use App\Support\Challenge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckinController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        $weekNumber = Challenge::currentWeekNumber();

        $canCheckIn = Challenge::isTuesday() && $weekNumber > 0 && !$user->hasCheckedInForWeek($weekNumber);

        return view('checkin.create', [
            'weekNumber' => $weekNumber,
            'canCheckIn' => $canCheckIn,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $weekNumber = Challenge::currentWeekNumber();

        $isAdminOverride = $user->is_admin && $request->boolean('override');

        if (!$isAdminOverride) {
            if (!Challenge::isTuesday()) {
                return back()->withErrors(['checkin' => 'Пријавувањата се дозволени само во вторник.']);
            }
            if ($weekNumber <= 0) {
                return back()->withErrors(['checkin' => 'Предизвикот сè уште не започнал.']);
            }
            if ($user->hasCheckedInForWeek($weekNumber)) {
                return back()->withErrors(['checkin' => 'Веќе си се пријавил за оваа недела.']);
            }
        }

        $request->validate([
            'weight' => ['required', 'numeric', 'min:20', 'max:400'],
            'photo' => ['required', 'image', 'max:8192'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        $path = $request->file('photo')->store('photos/checkins', 'public');

        Checkin::create([
            'user_id' => $user->id,
            'week_number' => $weekNumber > 0 ? $weekNumber : 1,
            'checkin_date' => now()->toDateString(),
            'weight' => $request->weight,
            'photo' => $path,
            'note' => $request->note,
            'admin_override' => $isAdminOverride,
        ]);

        return redirect()->route('dashboard')->with('success', 'Неделното пријавување е прикачено. Браво!');
    }
}
