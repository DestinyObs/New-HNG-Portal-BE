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
    /**
     * @OA\Get(
     *     path="/api/faq",
     *     summary="Get list of FAQs",
     *     tags={"FAQ"},
     *
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         required=false,
     *         description="FAQ type: company or talent",
     *         @OA\Schema(
     *             type="string",
     *             enum={"company", "talent"}
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="FAQs retrieved successfully"
     *     )
     * )
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

    /**
     * @OA\Get(
     *     path="/api/faq/{faq}",
     *     summary="Get a single FAQ by ID",
     *     tags={"FAQ"},
     *
     *     @OA\Parameter(
     *         name="faq",
     *         in="path",
     *         required=true,
     *         description="FAQ ID",
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="FAQ retrieved successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="FAQ not found"
     *     )
     * )
     */

    public function show(Faq $faq)
    {
        return $this->successWithData($faq, 'FAQ retrieved successfully');
    }
}

