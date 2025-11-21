<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Services\Interfaces\CountryInterface;

class CountryController extends Controller
{
    public function __construct(
        private readonly CountryInterface $countryService
    ) {}

    /**
     * Display a listing of countries.
     */
    public function index()
    {
        $countries = $this->countryService->getAll();
        return $this->successWithData($countries, 'Countries retrieved successfully');
    }

    /**
     * Display the specified country.
     */
    public function show(Country $country)
    {
        return $this->successWithData($country, 'Country retrieved successfully');
    }
}

