<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertySearchController extends Controller
{
    public function __invoke(Request $request)
    {
        return Property::with('city')
            ->when($request->city, function($query) use ($request) {
                $query->where('city_id', $request->city);
            })
            ->when($request->country, function($query) use ($request) {
                // get properties that HAVE a relationship (belong to) with a city
                // WHERE that city must reside in the requested country
                $query->whereHas('city', fn($q) => $q->where('country_id', $request->country));
            })
            ->get();
    }
}
