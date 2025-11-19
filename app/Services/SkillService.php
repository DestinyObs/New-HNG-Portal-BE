<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Skill;
use App\Repositories\Interfaces\SkillRepositoryInterface;
use App\Services\Interfaces\SkillInterface;
use Illuminate\Database\Eloquent\Collection;

class SkillService implements SkillInterface
{
    public function __construct(
        private readonly SkillRepositoryInterface $skillRepository,
    ) {}

    public function getAll(): Collection
    {
        return $this->skillRepository->getAll();
    }

    public function findById(string $id): Skill
    {
        return $this->skillRepository->findById($id);
    }
}

