<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\State;
use App\Services\Interfaces\StateInterface;

class StateController extends Controller
{
    public function __construct(
        private readonly StateInterface $stateService
    ) {}

    /**
     * Display a listing of states.
     */
    public function index()
    {
        $states = $this->stateService->getAll();
        return $this->successWithData($states, 'States retrieved successfully');
    }

    /**
     * Display the specified state.
     */
    public function show(State $state)
    {
        return $this->successWithData($state, 'State retrieved successfully');
    }

    /**
     * Display states for a specific country.
     */
    public function getByCountry(Country $country)
    {
        $states = $this->stateService->getByCountryId($country->id);
        return $this->successWithData($states, 'States retrieved successfully');
    }
}

