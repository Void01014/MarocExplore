<?php

namespace Database\Seeders;

use App\Models\Destination;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DestinationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Agadir itinerary
        Destination::create([
            'itinerary_id' => 1,
            'name' => 'Agadir',
            'lodging' => 'Hotel Royal Atlas',
            'places' => ['Plage d\'Agadir', 'Souk El Had', 'Kasbah'],
            'activities' => ['surf', 'baignade', 'quad'],
            'food' => ['tagine de poisson', 'sardines grillées'],
        ]);

        Destination::create([
            'itinerary_id' => 1,
            'name' => 'Tiznit',
            'lodging' => 'Riad Tiznit',
            'places' => ['Médina', 'Source Bleue'],
            'activities' => ['balade', 'artisanat'],
            'food' => ['couscous', 'amlou'],
        ]);

        // Atlas itinerary
        Destination::create([
            'itinerary_id' => 2,
            'name' => 'Imlil',
            'lodging' => 'Kasbah du Toubkal',
            'places' => ['Jebel Toubkal', 'Village Aremd'],
            'activities' => ['randonnée', 'escalade'],
            'food' => ['tagine', 'pain berbère'],
        ]);

        Destination::create([
            'itinerary_id' => 2,
            'name' => 'Ouarzazate',
            'lodging' => 'Hotel Berbère Palace',
            'places' => ['Aït Benhaddou', 'Studio Atlas'],
            'activities' => ['visite culturelle', 'photographie'],
            'food' => ['méchoui', 'pastilla'],
        ]);

        // Marrakech itinerary
        Destination::create([
            'itinerary_id' => 3,
            'name' => 'Marrakech Médina',
            'lodging' => 'Riad Yasmine',
            'places' => ['Jemaa el-Fna', 'Majorelle', 'Bahia'],
            'activities' => ['shopping', 'hammam'],
            'food' => ['pastilla', 'tanjia'],
        ]);

        Destination::create([
            'itinerary_id' => 3,
            'name' => 'Essaouira',
            'lodging' => 'Hotel Sofitel',
            'places' => ['Médina', 'Port de pêche', 'Remparts'],
            'activities' => ['surf', 'balade'],
            'food' => ['fruits de mer', 'brochettes'],
        ]);
    }
}
