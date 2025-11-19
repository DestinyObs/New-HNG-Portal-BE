<?php

namespace App\Services\Interfaces;

interface SkillServiceInterface
{
        public function getAllSkills();
        public function createSkill(array $data);
        public function getSkillById(string $id);
        public function updateSkill(string $id, array $data);
        public function deleteSkill(string $id);
}
