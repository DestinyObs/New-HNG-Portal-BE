<?php

namespace App\Services\Interfaces;

use App\Models\Faq;
use Illuminate\Database\Eloquent\Collection;

interface FaqInterface
{
    public function list(?string $type = null);
    public function find(Faq $faq): Faq;
}

