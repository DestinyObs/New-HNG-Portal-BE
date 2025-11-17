<?php

namespace App\Http\Controllers;

use App\Models\Waitlist;
use App\Models\WaitlistUser;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WaitlistController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:waitlists,email',
            'role' => ['required', Rule::in(['talent', 'company'])],
        ]);

        $waitlist = Waitlist::create($data);


        return response()->json([
            'message' => 'Successfully joined the waitlist!',
            'data' => $waitlist
        ], 201);
    }
}
