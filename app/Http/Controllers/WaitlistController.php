<?php

namespace App\Http\Controllers;

use App\Mail\WaitlistJoined;
use App\Models\Waitlist;
use App\Models\WaitlistUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class WaitlistController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
       	    'full_name' => 'required|string|max:255|min:2',
            'email' => 'required|email|unique:waitlists,email',
            'role' => ['required', Rule::in(['talent', 'company'])],
        ], [
            'email.unique' => 'This email is already on our waitlist!',
            'role.in' => 'Please select either talent or company role.',
        ]);

        $waitlist = Waitlist::create($data);

        Mail::to($waitlist->email)->send(new WaitlistJoined($waitlist));


        return response()->json([
            'message' => 'Successfully joined the waitlist!',
            'data' => $waitlist
        ], 201);
    }
}

