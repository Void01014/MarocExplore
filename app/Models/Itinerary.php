<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function scopeFilterBytitle($query, $title){
        if($title){
            return $query->where('title', 'LIKE', "%{$title}%");
        }
    }

    public function scopeFilterByCategory($query, $category){
        if($category){
            return $query->whereRelation('category', 'name', $category);
        }
    }

    public function scopeFilterByDuration($query, $duration){
        if($duration){
            return $query->where('duration', $duration);
        }
    }
}
