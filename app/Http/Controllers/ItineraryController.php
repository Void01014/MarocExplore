<?php

namespace App\Http\Controllers;

use App\Models\Itinerary;
use Illuminate\Http\Request;

class ItineraryController extends Controller
{
    public function create(Request $request)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
        ];

        $validated = $request->validate($rules);

        $itenerary = Itinerary::create([
            'user_id' => '' ?? '',
            'name' => '' ?? '',
            'title' => '' ?? '',
            'duration' => '' ?? '',
            'image' => '' ?? '',
            'category_id' => '' ?? ''
        ]);

        foreach ($validated['destinations'] as $destination) {
            $destination = '';
        }
    }
}
