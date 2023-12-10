<?php

namespace Tests\Feature;

use App\Models\Tour;
use App\Models\Travel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TourListTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_tours_list_by_travel_slug_returns_correct_tours(): void
    {
        $travel = Travel::factory()->create();
        $tour   = Tour::factory()->create(['travel_id' => $travel->id]);

        $response = $this->get('/api/v1/travels/'. $travel->slug .'/tours');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment(['id' => $tour->id]);
    }

    public function test_tour_price_is_shown_correctly() {

        $travel = Travel::factory()->create();
        Tour::factory()->create([
            'travel_id' => $travel->id,
            'price'     => 123.45
        ]);

        $response = $this->get('/api/v1/travels/'. $travel->slug .'/tours');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment(['price' => '123.45']);
    }

    public function test_tours_list_returns_pagination() {

        // Pagination By Default is 15 so we make 16 to make 2 pages

        $travel = Travel::factory()->create();
        Tour::factory(16)->create(['travel_id' => $travel->id]);

        $response = $this->get('/api/v1/travels/'. $travel->slug .'/tours');

        $response->assertStatus(200);
        $response->assertJsonCount(15, 'data');
        $response->assertJsonPath('meta.last_page', 2);
    }

    public function test_tours_list_sorts_by_starting_date_correctly() {
        $travel = Travel::factory()->create();
        $lastTour = Tour::factory()->create([
            'travel_id'     => $travel->id,
            'starting_date' => now()->addDays(2),
            'ending_date'   => now()->addDays(3)
        ]);

        $earlierTour = Tour::factory()->create([
            'travel_id'     => $travel->id,
            'starting_date' => now(),
            'ending_date'   => now()->addDays(1)
        ]);

        $response = $this->get('/api/v1/travels/'. $travel->slug .'/tours');

        $response->assertStatus(200);
        $response->assertJsonPath('data.0.id', $earlierTour->id);
        $response->assertJsonPath('data.1.id', $lastTour->id);
    }

    public function test_tours_list_sorts_by_Price_correctly() {
        $travel = Travel::factory()->create();

        $expensiveTour = Tour::factory()->create([
            'travel_id'     => $travel->id,
            'price' => 200
        ]);

        $cheapLaterTour = Tour::factory()->create([
            'travel_id'     => $travel->id,
            'price' => 100,
            'starting_date' => now()->addDays(2),
            'ending_date'   => now()->addDays(3)
        ]);

        $cheapEarlierTour = Tour::factory()->create([
            'travel_id'     => $travel->id,
            'price' => 100,
            'starting_date' => now(),
            'ending_date'   => now()->addDays(1)
        ]);

        $response = $this->get('/api/v1/travels/'. $travel->slug .'/tours?sortBy=price&sortOrder=ASC');

        $response->assertStatus(200);
        $response->assertJsonPath('data.0.id', $cheapEarlierTour->id);
        $response->assertJsonPath('data.1.id', $cheapLaterTour->id);
        $response->assertJsonPath('data.2.id', $expensiveTour->id);
    }
}
