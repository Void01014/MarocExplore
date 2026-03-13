<?php

use Illuminate\Support\Facades\DB;

DB::table('itineraries')
    ->leftJoin('wishlists', 'itineraries.id', '=', 'wishlists.itinerary_id')
    ->select('itineraries.*', DB::raw('count(wishlists.id) as wishlists_count'))
    ->groupBy('itineraries.id')
    ->orderBy('wishlists_count', 'desc')
    ->get();

//////////////

DB::table('itineraries')
    ->join('categories', 'itineraries.category_id', '=', 'categories.id')
    ->select('categories.name', DB::raw('count(*) as total'))
    ->groupBy('categories.name')
    ->get();

DB::table('users')
    ->select(
        DB::raw('EXTRACT(YEAR FROM created_at) as year'),
        DB::raw('EXTRACT(MONTH FROM created_at) as month'),
        DB::raw('count(*) as total')
    )
    ->groupBy('year', 'month')
    ->orderBy('year')
    ->orderBy('month')
    ->get();

DB::table('users')
    ->select(
        DB::raw('EXTRACT(YEAR FROM created_at) as year'),
        DB::raw('EXTRACT(MONTH FROM created_at) as month'),
        DB::raw('count(*) as total')
    )
    ->groupBy('year', 'month')
    ->orderBy('year')
    ->orderBy('month')
    ->get();
