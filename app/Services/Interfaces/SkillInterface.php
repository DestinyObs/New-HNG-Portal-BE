<?php

declare(strict_types=1);

namespace App\Services\Interfaces;

use App\Models\Skill;
use Illuminate\Database\Eloquent\Collection;

interface SkillInterface
{
    public function getAll(): Collection;
    public function findById(string $id): Skill;
}

