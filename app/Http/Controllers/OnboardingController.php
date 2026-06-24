<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class OnboardingController extends Controller
{
    public function showPasswordForm()
    {
        $user = Auth::user();
        if (!$user->must_change_password) {
            return redirect()->route('onboarding.start');
        }
        return view('onboarding.password');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->must_change_password = false;
        $user->save();

        return redirect()->route('onboarding.start')
            ->with('success', 'Лозинката е сменета. Сега внеси ги твоите почетни податоци за предизвикот.');
    }

    public function showStartForm()
    {
        $user = Auth::user();

        if ($user->must_change_password) {
            return redirect()->route('onboarding.password');
        }

        if ($user->onboarding_completed) {
            return redirect()->route('dashboard');
        }

        return view('onboarding.start');
    }

    public function submitStart(Request $request)
    {
        $user = Auth::user();

        if ($user->onboarding_completed || $user->starting_weight) {
            return redirect()->route('dashboard');
        }

        $request->validate([
            'starting_weight' => ['required', 'numeric', 'min:20', 'max:400'],
            'starting_photo' => ['required', 'image', 'max:8192'],
            'goal_note' => ['nullable', 'string', 'max:255'],
        ]);

        $path = $request->file('starting_photo')->store('photos/starting', 'public');

        $user->starting_weight = $request->starting_weight;
        $user->starting_photo = $path;
        $user->goal_note = $request->goal_note;
        $user->onboarding_completed = true;
        $user->save();

        return redirect()->route('dashboard')->with('success', 'Добредојде! Твојот предизвик започна.');
    }
}
