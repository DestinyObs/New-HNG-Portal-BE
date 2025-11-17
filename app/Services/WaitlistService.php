<?php

namespace App\Services;

use App\Mail\WaitlistJoined;
use App\Models\Waitlist;
use App\Services\Interfaces\WaitlistInterface;
use Illuminate\Support\Facades\Mail;

class WaitlistService implements WaitlistInterface
{
    public function create(array $data): Waitlist
    {
        $waitlist = Waitlist::query()->create($data);

        Mail::to($waitlist->email)->send(new WaitlistJoined($waitlist));

        return $waitlist;
    }
}
