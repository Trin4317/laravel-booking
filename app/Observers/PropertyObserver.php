<?php

namespace App\Observers;

use App\Models\Property;
use App\Services\Maps;

class PropertyObserver
{
    public function creating(Property $property)
    {
        // add the owner automatically
        if (auth()->check()) {
            $property->owner_id = auth()->id();
        }

        // add geocodes if not provided
        if (is_null($property->lat) && is_null($property->long)) {
            $coordinates = Maps::geocodeAddress(
                $property->address_street,
                $property->address_postcode,
                $property->city->name,
                $property->city->country->name
            );

            $property->lat  = $coordinates['lat'];
            $property->long = $coordinates['lng'];
        }
    }

    /**
     * Handle the Property "created" event.
     */
    public function created(Property $property): void
    {
        //
    }

    /**
     * Handle the Property "updated" event.
     */
    public function updated(Property $property): void
    {
        //
    }

    /**
     * Handle the Property "deleted" event.
     */
    public function deleted(Property $property): void
    {
        //
    }

    /**
     * Handle the Property "restored" event.
     */
    public function restored(Property $property): void
    {
        //
    }

    /**
     * Handle the Property "force deleted" event.
     */
    public function forceDeleted(Property $property): void
    {
        //
    }
}
