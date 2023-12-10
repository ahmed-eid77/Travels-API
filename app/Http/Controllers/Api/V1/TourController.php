<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ToursListRequest;
use App\Http\Resources\TourResource;
use App\Models\Tour;
use App\Models\Travel;



class TourController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Travel $travel, ToursListRequest $request)
    {
        $tours = Tour::where('travel_id', $travel->id)
                        ->when($request->priceFrom, function($query) use($request) {
                            $query->where('price', '>=', $request->priceFrom * 100);
                        })
                        ->when($request->priceTo, function($query) use($request) {
                            $query->where('price', '<=', $request->priceTo * 100);
                        })
                        ->when($request->dateFrom, function($query) use($request) {
                            $query->where('starting_date', '>=', $request->dateForm);
                        })
                        ->when($request->dateTo, function($query) use($request) {
                            $query->where('starting_date', '<=' , $request->dateForm);
                        })
                        ->when($request->sortBy && $request->sortOrder, function($query) use($request) {
                            $query->orderBy($request->sortBy, $request->sortOrder);
                        })
                        ->orderBy('starting_date')
                        ->paginate();
        return TourResource::collection($tours);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ToursListRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ToursListRequest $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
