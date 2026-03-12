<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Itinerary;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItineraryController extends Controller
{

    public function index()
    {
        return response()->json(Itinerary::with('destinations')->get());
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'duration' => ['required', 'integer'],
            'image' => ['required', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'destinations' => ['required', 'array', 'min:2'],
            'destinations.*.name' => ['required', 'string'],
            'destinations.*.lodging' => ['nullable', 'string'],
            'destinations.*.places' => ['nullable', 'array'],
            'destinations.*.activities' => ['nullable', 'array'],
            'destinations.*.food' => ['nullable', 'array'],
        ]);

        $itinerary = Itinerary::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'duration' => $validated['duration'],
            'image' => $validated['image'],
            'category_id' => $validated['category_id'],
        ]);

        foreach ($validated['destinations'] as $destination) {
            Destination::create([
                'itinerary_id' => $itinerary->id,
                'name' => $destination['name'],
                'lodging' => $destination['lodging'] ?? null,
                'places' => $destination['places'] ?? [],
                'activities' => $destination['activities'] ?? [],
                'food' => $destination['food'] ?? [],
            ]);
        }

        return response()->json($itinerary->load('destinations'), 201);
    }

    public function show($id)
    {
        $itinerary = Itinerary::with('destinations')->find($id);

        if (!$itinerary) {
            return response()->json(['message' => 'Itinerary not found'], 404);
        }

        return response()->json($itinerary);
    }

    public function update(Request $request, $id)
    {
        $itinerary = Itinerary::find($id);

        if (!$itinerary) {
            return response()->json(['message' => 'Itinerary not found'], 404);
        }

        if ($itinerary->user_id !== Auth::id()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'title' => ['sometimes', 'string', 'max:255'],
            'duration' => ['sometimes', 'integer'],
            'image' => ['sometimes', 'string'],
            'category_id' => ['sometimes', 'exists:categories,id'],
        ]);

        $itinerary->update($validated);

        return response()->json($itinerary->load('destinations'));
    }

    public function destroy($id)
    {
        $itinerary = Itinerary::find($id);

        if (!$itinerary) {
            return response()->json(['message' => 'Itinerary not found'], 404);
        }

        if ($itinerary->user_id !== Auth::id()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $itinerary->delete();

        return response()->json(null, 204);
    }

    public function addToWishlist($id)
    {
        $itinerary = Itinerary::find($id);

        if (!$itinerary) {
            return response()->json(['message' => 'Itinerary not found'], 404);
        }

        $already = Wishlist::where('user_id', Auth::id())
            ->where('itinerary_id', $id)
            ->exists();

        if ($already) {
            return response()->json(['message' => 'Already in wishlist'], 409);
        }

        Wishlist::create([
            'user_id' => Auth::id(),
            'itinerary_id' => $id,
        ]);

        return response()->json(['message' => 'Added to wishlist'], 201);
    }

    public function removeFromWishlist($id)
    {
        $itinerary = Itinerary::find($id);

        if (!$itinerary) {
            return response()->json(['message' => 'Itinerary not found'], 404);
        }

        $delete = Wishlist::where('user_id', Auth::id())
            ->where('itinerary_id', $id)
            ->delete();

        if (!$delete) {
            return response()->json(['message' => 'Not in Wishlist'], 404);
        }

        return response()->json(['message' => 'Removed From Wishlist'], 200);
    }
}
