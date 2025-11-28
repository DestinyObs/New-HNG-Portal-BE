<?php

namespace App\Http\Controllers\Talent;

use App\Http\Controllers\Controller;
use App\Http\Requests\Talent\StorePortfolioRequest;
use App\Http\Requests\Talent\UpdatePortfolioRequest;
use App\Models\Portfolio;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $portfolios = $user->portfolios;
        if ($portfolios->isEmpty()) {
            return $this->notFound('Not found, No portfolios added yet!');
        }
        return $this->successWithData($portfolios, 'User portfolio retrieved successfully');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePortfolioRequest $request)
    {

        $data = $request->validated();
        $user = $request->user();
        $data['user_id'] = $user->id;

        // Create portfolio
        $portfolio = Portfolio::create($data);

        // Check for banner image
        if ($request->hasFile('banner_image')) {
            $url = $portfolio
                ->addMediaFromRequest('banner_image')
                ->toMediaCollection('banner_image');

            // Update model with banner image url - optional
            $portfolio->update([
                'banner_url' => $url?->original_url
            ]);
        }
        // Get media file
        $portfolio->getMedia();
        // return response
        return $this->successWithData($portfolio, 'User portfolio stored successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Portfolio $portfolio)
    {
        $user = $request->user();

        $portfolio = Portfolio::where('id', $portfolio->id)
            ->where('user_id', $user->id)
            ->firstOrFail();
        // Get media file
        $portfolio->getMedia();
        // return response
        return $this->successWithData($portfolio, 'User portfolio retrieved successfully');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePortfolioRequest $request, Portfolio $portfolio)
    {
        $data = $request->validated();
        $user = $request->user();

        $portfolio = Portfolio::where('id', $portfolio->id)
            ->where('user_id', $user->id)
            ->firstOrFail();
        // Check for banner image
        if ($request->hasFile('banner_image')) {
            // Delete previous files
            $portfolio->media->each->delete();
            // Upload new file
            $url = $portfolio
                ->addMediaFromRequest('banner_image')
                ->toMediaCollection('banner_image');
            // Update the banner url
            $data['banner_url'] = $url?->original_url;
        }
        // Update model with banner image url - optional
        $portfolio->update($data);
        // Use 'refresh()' to reload the current instance with new data
        $portfolio->refresh();

        // Get media file
        $portfolio->getMedia();
        // return response
        return $this->successWithData($portfolio, 'User portfolio updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Portfolio $portfolio)
    {
        $user = $request->user();

        $experience = Portfolio::where('id', $portfolio)
            ->where('user_id', $user->id)
            ->firstOrFail();

        if (!$experience) {
            return $this->notFound('portfolio not found!');
        }
        $experience->delete();
        return $this->noContent();
    }
}
