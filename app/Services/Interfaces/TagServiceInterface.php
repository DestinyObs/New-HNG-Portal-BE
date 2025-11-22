<?php

namespace App\Services\Interfaces;

interface TagServiceInterface
{
      public function getAllTags();
      public function createTag(array $data);
      public function getTagById(string $id);
      public function updateTag(string $id, array $data);
      public function deleteTag(string $id);
}
