<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Services\Interfaces\FaqInterface;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class FaqController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly FaqInterface $faqService
    ) {}

    /**
     * GET /faq?type=company|talent
     */

    public function index(Request $request)
    {
        $type = $request->query('type', null);
        $faqs = $this->faqService->list($type);

        return $this->successWithData($faqs, 'FAQs retrieved successfully');
    }

    /**
     * GET /faq/{faq}
     */

    public function show(Faq $Faq)
    {
        return $this->successWithData($Faq, 'FAQ retrieved successfully');
    }
}

