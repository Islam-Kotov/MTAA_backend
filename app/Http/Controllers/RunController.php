<?php

namespace App\Http\Controllers;

use App\Models\Run;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RunController extends Controller
{

    public function store(Request $request)
    {
        $validated = $request->validate([
            'distance' => 'required|numeric',
            'steps' => 'required|integer',
            'duration' => 'required|integer',
            'avg_speed' => 'required|numeric',
            'started_at' => 'required|date',
        ]);

        $run = Run::create([
            'user_id' => Auth::id(),
            ...$validated,
        ]);

        return response()->json($run, 201);
    }

    public function index()
    {
        $runs = Run::where('user_id', Auth::id())
            ->orderByDesc('started_at')
            ->get();

        return response()->json($runs);
    }
}
