<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Itinerary',
    properties: [
        new OA\Property(property: 'id', type: 'integer'),
        new OA\Property(property: 'title', type: 'string'),
        new OA\Property(property: 'category_id', type: 'integer'),
        new OA\Property(property: 'duration', type: 'integer'),
        new OA\Property(property: 'image', type: 'string'),
        new OA\Property(property: 'user_id', type: 'integer'),
        new OA\Property(property: 'destinations', type: 'array', items: new OA\Items(ref: '#/components/schemas/Destination')),
    ]
)]

class Itinerary extends Model
{
    /** @use HasFactory<\Database\Factories\ItineraryFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'category_id', 'title', 'duration', 'image'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function destinations()
    {
        return $this->hasMany(Destination::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    ///////////////////////////////////////

    public function scopeFilterBytitle($query, $title)
    {
        if ($title) {
            return $query->where('title', 'LIKE', "%{$title}%");
        }
    }

    public function scopeFilterByCategory($query, $category)
    {
        if ($category) {
            return $query->whereRelation('category', 'name', $category);
        }
    }

    public function scopeFilterByDuration($query, $duration)
    {
        if ($duration) {
            return $query->where('duration', $duration);
        }
    }
}
