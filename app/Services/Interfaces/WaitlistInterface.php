<?php

namespace App\Services\Interfaces;

use App\Models\Waitlist;

interface WaitlistInterface
{
    public function create(array $data): Waitlist;
    // public function show(int $id): Waitlist;
}
