<?php

namespace App\Http\Controllers;

use App\Models\Itinerary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItineraryController extends Controller
{
    public function create(Request $request)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'],
        ];

        $validated = $request->validate($rules);

        $itenerary = Itinerary::create([
            'user_id' => Auth::id(),
            'title' => $request['title'] ?? '',
            'duration' => $request['duration'] ?? 3,
            'image' => $request['image'] ?? '',
            'category_id' => $request['category_id']
        ]);

        foreach ($validated['destinations'] as $destination) {
            $destination = '';  
        }
    }
}
