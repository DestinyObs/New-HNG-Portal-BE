<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\Interfaces\UserInterface;

class UserController extends Controller
{
    public function __construct(
        private readonly UserInterface $userService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //   
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return $this->successWithData($user, 'User retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $userCredentials = $this->userService->create($request->validated());
        // dd($userCredentials);
        return $this->created('User created successfully', $userCredentials);
    }

    public function logout()
    {
        $this->userService->logout();

        return $this->success('Logged out successfully');
    }
}
