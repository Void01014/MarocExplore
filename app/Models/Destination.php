<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Destination',
    properties: [
        new OA\Property(property: 'id', type: 'integer'),
        new OA\Property(property: 'name', type: 'string'),
        new OA\Property(property: 'lodging', type: 'string'),
        new OA\Property(property: 'places', type: 'array', items: new OA\Items(type: 'string')),
        new OA\Property(property: 'activities', type: 'array', items: new OA\Items(type: 'string')),
        new OA\Property(property: 'food', type: 'array', items: new OA\Items(type: 'string')),
    ]
)]
class Destination extends Model
{
    /** @use HasFactory<\Database\Factories\DestinationFactory> */
    use HasFactory;

    protected $fillable = ['itinerary_id', 'name', 'lodging', 'places', 'activities', 'food'];

    protected $casts = [
        'places' => 'array',
        'activities' => 'array',
        'food' => 'array',
    ];

    public function itinerary()
    {
        return $this->belongsTo(Itinerary::class);
    }
}
