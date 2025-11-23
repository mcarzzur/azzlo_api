<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Habit;
use App\Models\HabitCompletion;
use Illuminate\Support\Facades\Auth;

class HabitController extends Controller
{
    public function index()
    {
        $habits = Auth::user()->habits()->with('completions')->get();
        return response()->json($habits);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|string',
            'description'=>'nullable|string|max:255',
            'icon'=>'nullable|string',
            'date_start'=>'date',
            'date_end'=>'date',
            'frequency'=>'required|string',
        ]);

        $habit = Auth::user()->habits()->create($request->all());
        return response()->json($habit);
    }

    public function update(Request $request, $id)
    {
        $habit = Auth::user()->habits()->findOrFail($id);
        $habit->update($request->all());
        return response()->json($habit);
    }

    public function destroy($id)
    {
        $habit = Auth::user()->habits()->findOrFail($id);
        $habit->delete();
        return response()->json(['message'=>'Hábito eliminado']);
    }

    public function complete($id)
    {
        $habit = Auth::user()->habits()->findOrFail($id);
        $completion = HabitCompletion::create([
            'habit_id'=>$habit->id,
            'completed_at'=>now()->toDateString(),
        ]);
        return response()->json($completion);
    }
}
