<?php

namespace App\Http\Controllers;

use App\Http\Requests\WaitlistRequest;
use App\Models\Waitlist;
use App\Services\Interfaces\WaitlistInterface;

class WaitlistController extends Controller
{
    public function __construct(
        private readonly WaitlistInterface $waitlistService
    ) {}

    public function show(Waitlist $waitlist)
    {
        return $this->successWithData($waitlist, 'Waitlist retrieved successfully');
    }

    public function store(WaitlistRequest $request)
    {
        $waitlist = $this->waitlistService->create($request->validated());

        return $this->created('Waitlist entry created successfully', $waitlist);
    }
}
