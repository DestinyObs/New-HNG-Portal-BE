<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use App\Services\Interfaces\SkillInterface;

class SkillController extends Controller
{
    public function __construct(
        private readonly SkillInterface $skillService
    ) {}

    /**
     * Display a listing of skills.
     */
    public function index()
    {
        $skills = $this->skillService->getAll();
        return $this->successWithData($skills, 'Skills retrieved successfully');
    }

    /**
     * Display the specified skill.
     */
    public function show(Skill $skill)
    {
        return $this->successWithData($skill, 'Skill retrieved successfully');
    }
}

