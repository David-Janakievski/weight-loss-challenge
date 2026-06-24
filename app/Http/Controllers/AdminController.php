<?php

namespace App\Http\Controllers;

use App\Models\Checkin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        $users = User::where('is_admin', false)->get();
        $checkins = Checkin::with('user')->latest('checkin_date')->get();

        return view('admin.dashboard', compact('users', 'checkins'));
    }

    public function editUser(User $user)
    {
        return view('admin.edit_user', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'starting_weight' => ['nullable', 'numeric', 'min:20', 'max:400'],
            'goal_note' => ['nullable', 'string', 'max:255'],
            'reset_password' => ['nullable', 'boolean'],
        ]);

        $user->name = $request->name;
        $user->starting_weight = $request->starting_weight;
        $user->goal_note = $request->goal_note;

        if ($request->boolean('reset_password')) {
            $user->password = Hash::make('123456');
            $user->must_change_password = true;
        }

        if ($request->hasFile('starting_photo')) {
            $user->starting_photo = $request->file('starting_photo')->store('photos/starting', 'public');
        }

        $user->save();

        return redirect()->route('admin.dashboard')->with('success', 'Корисникот е ажуриран.');
    }

    public function deleteCheckin(Checkin $checkin)
    {
        $checkin->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Пријавувањето е избришано.');
    }

    public function editCheckin(Checkin $checkin)
    {
        return view('admin.edit_checkin', compact('checkin'));
    }

    public function updateCheckin(Request $request, Checkin $checkin)
    {
        $request->validate([
            'weight' => ['required', 'numeric', 'min:20', 'max:400'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        $checkin->weight = $request->weight;
        $checkin->note = $request->note;

        if ($request->hasFile('photo')) {
            $checkin->photo = $request->file('photo')->store('photos/checkins', 'public');
        }

        $checkin->save();

        return redirect()->route('admin.dashboard')->with('success', 'Пријавувањето е ажурирано.');
    }

    public function photos()
    {
        $checkins = Checkin::with('user')->latest('checkin_date')->get();
        return view('admin.photos', compact('checkins'));
    }
}
