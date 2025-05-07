<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function showPrivate(Request $request, string $filename)
    {
        $path = 'users/' . auth()->user()->id . '/' . $filename;

        if(!Storage::disk('private')->exists($path))
        {
            abort(404);
        }

        return response()->file(Storage::disk('private')->path($path));
    }
}
