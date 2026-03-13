<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Itinerary;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;
use Ramsey\Uuid\Exception\NameException;

class ItineraryController extends Controller
{
    #[OA\Get(path: '/api/itineraries/', summary: 'Get itineraries with filtering', security: [['bearerAuth' => []]], tags: ['Itinerary'])]
    #[OA\Response(
        response: 200,
        description: 'Itineraries fetched',
        content: new OA\JsonContent(ref: '#/components/schemas/Itinerary')
    )]

    public function index(Request $request)
    {
        $search = $request->query('search');
        $category = $request->query('category');
        $duration = $request->query('duration');

        $itineraries = Itinerary::with('destinations')
            ->with('category:id,name')
            ->filterByTitle($search)
            ->filterByCategory($category)
            ->filterByDuration($duration)->get();

        return response()->json($itineraries);
    }

    #[OA\Post(path: '/api/itineraries/', summary: 'Create a new itinerary', security: [['bearerAuth' => []]], tags: ['Itinerary'])]
    #[OA\RequestBody(
        required: true,
        content: new OA\MediaType(
            mediaType: 'multipart/form-data',
            schema: new OA\Schema(ref: '#/components/schemas/Itinerary')
        )
    )]
    #[OA\Response(
        response: 201,
        description: 'Itinerary Created',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'Itinerary', ref: '#/components/schemas/Itinerary')
            ]
        )
    )]
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

    #[OA\Get(path: '/api/itineraries/{id}', summary: 'Get itinerary by id', security: [['bearerAuth' => []]], tags: ['Itinerary'])]
    #[OA\Parameter(
        name: 'id',
        description: 'The ID of the itinerary',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Response(
        response: 200,
        description: 'Itinerary fetched',
        content: new OA\JsonContent(ref: '#/components/schemas/Itinerary')
    )]

    public function show($id)
    {
        $itinerary = Itinerary::with('destinations')->find($id);

        if (!$itinerary) {
            return response()->json(['message' => 'Itinerary not found'], 404);
        }

        return response()->json($itinerary);
    }

    #[OA\Put(path: '/api/itineraries/{id}', summary: 'Update an itinerary', security: [['bearerAuth' => []]], tags: ['Itinerary'])]
    #[OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\RequestBody(
        content: new OA\MediaType(
            mediaType: 'multipart/form-data',
            schema: new OA\Schema(
                properties: [
                    new OA\Property(property: 'title', type: 'string', example: 'Updated Trip Title'),
                    new OA\Property(property: 'duration', type: 'integer', example: 5),
                    new OA\Property(property: 'image', type: 'string'),
                    new OA\Property(property: 'category_id', type: 'integer', example: 1),
                ]
            )
        )
    )]
    #[OA\Response(response: 200, description: 'Updated successfully', content: new OA\JsonContent(ref: '#/components/schemas/Itinerary'))]
    #[OA\Response(response: 403, description: 'Forbidden')]
    #[OA\Response(response: 404, description: 'Not Found')]

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

    #[OA\Delete(path: '/api/itineraries/{id}', summary: 'Delete an itinerary', security: [['bearerAuth' => []]], tags: ['Itinerary'])]
    #[OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\Response(response: 204, description: 'Deleted successfully')]
    #[OA\Response(response: 403, description: 'Forbidden')]

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

    #[OA\Get(path: '/api/wishlist', summary: 'Get user wishlist', security: [['bearerAuth' => []]], tags: ['Wishlist'])]
    #[OA\Response(response: 200, description: 'List of wishlisted itineraries')]

    public function wishlist()
    {
        $wishlist = Wishlist::where('user_id', Auth::id())
            ->with('itinerary.destinations')
            ->get();

        return response()->json($wishlist);
    }

    #[OA\Post(path: '/api/wishlist/{id}', summary: 'Add itinerary to wishlist', security: [['bearerAuth' => []]], tags: ['Wishlist'])]
    #[OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\Response(response: 201, description: 'Added successfully')]
    #[OA\Response(response: 409, description: 'Already in wishlist')]


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

    #[OA\Delete(path: '/api/wishlist/{id}', summary: 'Remove from wishlist', security: [['bearerAuth' => []]], tags: ['Wishlist'])]
    #[OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\Response(response: 200, description: 'Removed successfully')]


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
