<?php

namespace Database\Seeders;

use App\Models\Itinerary;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItinerarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Itinerary::create([
            'user_id' => 1,
            'category_id' => 1,
            'title' => 'Escapade à Agadir',
            'duration' => 5,
            'image' => 'agadir.jpg',
        ]);

        Itinerary::create([
            'user_id' => 1,
            'category_id' => 2,
            'title' => 'Aventure dans le Haut Atlas',
            'duration' => 7,
            'image' => 'atlas.jpg',
        ]);

        Itinerary::create([
            'user_id' => 1,
            'category_id' => 4,
            'title' => 'Découverte de Marrakech',
            'duration' => 3,
            'image' => 'marrakech.jpg',
        ]);
    }
}
