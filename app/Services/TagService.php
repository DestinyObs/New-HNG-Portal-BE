<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Tag;
use App\Repositories\Interfaces\TagRepositoryInterface;
use App\Services\Interfaces\TagInterface;
use Illuminate\Database\Eloquent\Collection;

class TagService implements TagInterface
{
    public function __construct(
        private readonly TagRepositoryInterface $tagRepository,
    ) {}

    public function getAll(): Collection
    {
        return $this->tagRepository->getAll();
    }

    public function findById(string $id): Tag
    {
        return $this->tagRepository->findById($id);
    }
}

