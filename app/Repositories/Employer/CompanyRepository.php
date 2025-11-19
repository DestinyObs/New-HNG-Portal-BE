<?php

namespace App\Repositories\Employer;

use App\Models\Company;
use App\Repositories\Interfaces\CompanyRepositoryInterface;
use App\Services\Interfaces\UploaderInterface;
use App\Services\UploadDrivers\LocalUploader;
use App\Services\UploadDrivers\S3Uplodaer;
use Illuminate\Support\Str;

class CompanyRepository implements CompanyRepositoryInterface
{
    protected $uploadDisk;

    public function __construct(private Company $company) {
        $disk = config('company.logo_disk');

         $this->uploadDisk = match ($disk) {
            'local'      => new LocalUploader(),
            's3'         => new S3Uplodaer(),
            default      => new LocalUploader(),
        };
    }

    public function create(array $data)
    {
        $data['id'] = Str::uuid()->toString();

        return $this->company->create($data);
    }

    public function show(string $uuid)
    {
        return $this->company->find($uuid);
    }

    public function update(array $data, string $uuid)
    {
        $company = $this->findById($uuid);

        $company->update($data);

        return $company;
    }

    public function updateLogo($file, $uuid)
    {
        $logoUrl = $this->uploadDisk->upload($file);

        $company = $this->findById($uuid);

        $company->update([
            'logo_url' => $logoUrl
        ]);

        return $company;
    }

    private function findById(string $uuid): Company
    {
        return $this->company->findOrFail($uuid);
    }
}
