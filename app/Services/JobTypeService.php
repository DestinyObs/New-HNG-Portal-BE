<?php

namespace App\Services;

use App\Repositories\JobTypeRepository;

class JobTypeService
{

    protected $jobTypeRepository;

    /**
     * Create a new class instance.
     */
    public function __construct(JobTypeRepository $jobTypeRepository)
    {
        $this->jobTypeRepository = $jobTypeRepository;
    }


    // Add service methods for job type management here
    public function getAllJobTypes()
    {
        // Logic to retrieve all job types
        return $this->jobTypeRepository->fetchAll();
    }

    public function createJobType(array $data)
    {
        // Logic to create a new job type
        return $this->jobTypeRepository->create($data);

    }

    public function getJobTypeById(string $id)
    {
        // Logic to retrieve a job type by ID
        return $this->jobTypeRepository->findById($id);
    }


    public function updateJobType(string $id, array $data)
    {
        // Logic to update a job type
        return $this->jobTypeRepository->update($id, $data);
    }

    public function deleteJobType(string $id)
    {
        // Logic to delete a job type
        return $this->jobTypeRepository->destroy($id);
    }
}
<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\JobType;
use App\Repositories\Interfaces\JobTypeRepositoryInterface;
use App\Services\Interfaces\JobTypeInterface;
use Illuminate\Database\Eloquent\Collection;

class JobTypeService implements JobTypeInterface
{
    public function __construct(
        private readonly JobTypeRepositoryInterface $jobTypeRepository,
    ) {}

    public function getAll(): Collection
    {
        return $this->jobTypeRepository->getAll();
    }

    public function findById(string $id): JobType
    {
        return $this->jobTypeRepository->findById($id);
    }
}

