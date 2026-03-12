<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
