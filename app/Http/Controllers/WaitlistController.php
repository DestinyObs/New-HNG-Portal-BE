<?php

namespace App\Http\Controllers;

use App\Enums\Http;
use App\Http\Requests\WaitlistRequest;
use App\Models\Waitlist;
use App\Responses\SuccessResponse;
use App\Services\Interfaces\WaitlistInterface;

class WaitlistController extends Controller
{
    public function __construct(
        private readonly WaitlistInterface $waitlistService
    ){}

    public function show(Waitlist $waitlist)
    {
        return SuccessResponse::make($waitlist);
    }


    public function store(WaitlistRequest $request)
    {
       return SuccessResponse::make(
            $this->waitlistService->create($request->validated(),
       ), Http::CREATED);

    }
}
