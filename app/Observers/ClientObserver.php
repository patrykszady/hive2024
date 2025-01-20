<?php

namespace App\Observers;

use App\Models\Client;

class ClientObserver
{
    /**
     * Handle the Client "created" event.
     *
     * @return void
     */
    public function created(Client $client)
    {
        //when creating from VendorRegistration with vendor_id
        //attach all $adding_vendor->users to $client
        if (! is_null($client->vendor)) {
            $client->users()->attach($client->vendor->users()->employed()->pluck('users.id')->toArray());
        }
    }

    public function creating(Client $client) {}

    /**
     * Handle the Client "updated" event.
     *
     * @return void
     */
    public function updated(Client $client)
    {
        //
    }

    /**
     * Handle the Client "deleted" event.
     *
     * @return void
     */
    public function deleted(Client $client)
    {
        //
    }

    /**
     * Handle the Client "restored" event.
     *
     * @return void
     */
    public function restored(Client $client)
    {
        //
    }

    /**
     * Handle the Client "force deleted" event.
     *
     * @return void
     */
    public function forceDeleted(Client $client)
    {
        //
    }
}
