<?php

namespace App\Jobs;

use App\Models\Property;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdatePropertyJob implements ShouldQueue
{
    use Queueable;

    public function handle()
    {
        $today = now()->format('Y-m-d');

        Property::chunk(10, function ($properties) use ($today) {
            foreach ($properties as $property) {
                try {

                    $favoriteCount = $this->getFavouriteCount($property->id);

                    $reviewStats = $this->getReviewStats($property->id);
                    $reviewsCount = (int) $reviewStats->review_count;
                    $avgRating = $reviewStats->avg_rating;

                    $updates = [
                        'reviews_count' => $reviewsCount ?? 0,
                        'avg_rating' => $avgRating,
                        'get_favourite_count' => $favoriteCount,
                    ];
                    $property->update($updates);

                } catch (\Exception $e) {
                    Log::error("Failed to update property ID {$property->id}: " . $e->getMessage());
                }
            }
        });

    }

    private function getReviewStats($propertyId)
    {
        return DB::table('investor_reviews')
            ->where('property_id', $propertyId)
            ->select(
                DB::raw('COUNT(*) as review_count'),
                DB::raw('COALESCE(AVG(rating2), 0) as avg_rating')
            )
            ->first();
    }

    private function getFavouriteCount($propertyId): int
    {
        return DB::table('favourites')
            ->where('property_id', $propertyId)
            ->count() ?? 0;
    }
}
