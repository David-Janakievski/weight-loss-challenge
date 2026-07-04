<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use App\Models\MealComment;
use App\Support\Challenge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MealController extends Controller
{
    // Show a specific day's meals for the logged-in user
    public function day(int $day)
    {
        $user = Auth::user();
        $start = Challenge::startDate()->addDays($day - 1)->startOfDay();
        $end = $start->copy()->endOfDay();

        $meals = Meal::where('user_id', $user->id)
            ->whereBetween('eaten_at', [$start, $end])
            ->with('comments.user')
            ->orderBy('eaten_at')
            ->get();

        return view('meals.day', compact('meals', 'day', 'start'));
    }

    // Store a new meal
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'photo'       => 'nullable|image|max:5120',
            'eaten_at'    => 'required|date',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('meals', 'public');
        }

        $data['user_id'] = Auth::id();
        $data['eaten_at'] = $data['eaten_at'];

        Meal::create($data);

        // Figure out which day to redirect back to
        $day = Challenge::startDate()->diffInDays(\Carbon\Carbon::parse($data['eaten_at'])->startOfDay()) + 1;

        return redirect()->route('meals.day', $day)->with('success', 'Оброкот е додаден!');
    }

    // Delete a meal (owner only)
    public function destroy(Meal $meal)
    {
        abort_if($meal->user_id !== Auth::id() && !Auth::user()->is_admin, 403);
        if ($meal->photo) Storage::disk('public')->delete($meal->photo);
        $meal->delete();
        return back()->with('success', 'Оброкот е избришан.');
    }

    // Post or update a comment/rating on a meal
    public function comment(Request $request, Meal $meal)
    {
        $data = $request->validate([
            'rating'  => 'nullable|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        // One comment per user per meal — update or create
        MealComment::updateOrCreate(
            ['meal_id' => $meal->id, 'user_id' => Auth::id()],
            $data
        );

        return back()->with('success', 'Коментарот е зачуван!');
    }

    // Delete own comment
    public function deleteComment(MealComment $comment)
    {
        abort_if($comment->user_id !== Auth::id() && !Auth::user()->is_admin, 403);
        $comment->delete();
        return back()->with('success', 'Коментарот е избришан.');
    }
}
