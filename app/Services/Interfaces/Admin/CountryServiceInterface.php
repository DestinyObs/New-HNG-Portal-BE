<?php

declare(strict_types=1);

namespace App\Services\Interfaces\Admin;

interface CountryServiceInterface
{
    public function getAllCountries(): object|array;
}
