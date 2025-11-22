<?php

namespace App\Repositories;

use App\Models\Company;

class CompanyRepository
{
    public function getPending()
    {
        return Company::where('status', 'pending')->get();
    }

    public function find(string $uuid)
    {
        return Company::find($uuid);
    }

    public function updateVerification(string $uuid, array $data)
    {
        $company = Company::findOrFail($uuid);

        $company->update([
            'status' => $data['status'],  // "approved" | "rejected"
            'remarks' => $data['remarks'] ?? null,
        ]);

        return $company;
    }
}
