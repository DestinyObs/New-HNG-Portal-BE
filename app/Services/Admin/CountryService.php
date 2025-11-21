<?php

declare(strict_types=1);

namespace App\Services\Admin;

use App\Enums\Http;
use App\Models\User;
use App\Repositories\Interfaces\Admin\CountryRepositoryInterface;
use App\Services\Interfaces\Admin\CountryServiceInterface;
use App\Traits\UploadFile;
use Illuminate\Http\Request;

class CountryService implements CountryServiceInterface
{

    public function __construct(
        private readonly CountryRepositoryInterface $countryRepository,
    ) {}


    public function getAllCountries(): object|array
    {
        try {
            $countries = $this->countryRepository->fetchAll();

            return (object) [
                'success' => true,
                'status' => Http::OK,
                'data' => $countries,
                'message' => 'Countries fetched successfully.',
            ];
        } catch (\Exception $e) {
            return (object) [
                'success' => false,
                'status' => Http::INTERNAL_SERVER_ERROR,
                'message' => 'An error occurred while fetching countries.',
            ];
        }
    }
}