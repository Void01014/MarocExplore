<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *      @OA\Schema(
 *          title="user",
 *          description="User modal",
 *          @OA\Proprety(proprety="user_id", type="integer")
 *          @OA\Property(property="itinerary_id", type="integer"),
 *      )
 */
class Wishlist extends Model
{
    /** @use HasFactory<\Database\Factories\WishlistFactory> */
    use HasFactory;

    public $fillable = ['user_id', 'itinerary_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function itinerary()
    {
        return $this->belongsTo(Itinerary::class);
    }
}
