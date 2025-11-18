<?php

namespace App\Services;

use App\Models\Faq;
use App\Services\Interfaces\FaqInterface;
use Illuminate\Database\Eloquent\Collection;

class FaqService implements FaqInterface
{
    public function list(?string $type = null)
    {
        $query = Faq::query();

        if ($type) {
            $query->where('type', $type);
        }

        return $query->get();
    }

    public function find(Faq $faq): Faq
    {
        return $faq;
    }
}

