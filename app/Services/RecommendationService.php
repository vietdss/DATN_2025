<?php

namespace App\Services;

use App\Models\Item;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class RecommendationService
{
    /**
     * Get similar items using KNN algorithm
     * 
     * @param int $itemId The ID of the item to find similar items for
     * @param int $k Number of similar items to return
     * @param array $weights Feature weights for similarity calculation
     * @return Collection Collection of similar items
     */
    public function getSimilarItems(
        int $itemId,
        int $k = 4,
        array $weights = [
            'category' => 0.4,
            'title' => 0.3,
            'description' => 0.3
        ]
    ): Collection {
        // Get the target item
        $targetItem = Item::with('category')->findOrFail($itemId);

        // Get all other items except the target item
        $allItems = Item::with(['category', 'images'])
            ->where('id', '!=', $itemId)
            ->where('status', '!=', 'taken')            // Only consider available items
            ->get();

        if ($allItems->isEmpty()) {
            return collect([]);
        }

        // Calculate similarity scores for each item
        $itemsWithScores = $allItems->map(function ($item) use ($targetItem, $weights) {
            $score = $this->calculateSimilarityScore($targetItem, $item, $weights);
            return [
                'item' => $item,
                'score' => $score
            ];
        });

        // Sort by similarity score (descending)
        $sortedItems = $itemsWithScores->sortByDesc('score');

        // Return top k items
        return $sortedItems->take($k)->map(function ($itemWithScore) {
            return $itemWithScore['item'];
        });
    }

    /**
     * Calculate similarity score between two items
     * 
     * @param Item $item1 First item
     * @param Item $item2 Second item
     * @param array $weights Feature weights
     * @return float Similarity score (0-1)
     */
    private function calculateSimilarityScore(Item $item1, Item $item2, array $weights): float
    {
        $score = 0;

        // Category similarity (exact match)
        if ($item1->category_id === $item2->category_id) {
            $score += $weights['category'];
        }

        // Title similarity using Levenshtein distance
        $titleSimilarity = $this->calculateTextSimilarity($item1->title, $item2->title);
        $score += $weights['title'] * $titleSimilarity;

        // Description similarity using Levenshtein distance
        $descriptionSimilarity = $this->calculateTextSimilarity($item1->description, $item2->description);
        $score += $weights['description'] * $descriptionSimilarity;

        return $score;
    }

    /**
     * Calculate text similarity using Levenshtein distance
     * 
     * @param string $text1 First text
     * @param string $text2 Second text
     * @return float Similarity score (0-1)
     */
    private function calculateTextSimilarity(string $text1, string $text2): float
    {
        // Convert to lowercase and trim
        $text1 = mb_strtolower(trim($text1));
        $text2 = mb_strtolower(trim($text2));

        // If either string is empty, return 0
        if (empty($text1) || empty($text2)) {
            return 0;
        }

        // Calculate Levenshtein distance
        $levenshtein = levenshtein($text1, $text2);

        // Calculate max possible distance
        $maxLength = max(mb_strlen($text1), mb_strlen($text2));

        // Convert to similarity score (0-1)
        return 1 - ($levenshtein / $maxLength);
    }

    /**
     * Get items based on content-based filtering
     * 
     * @param int $userId User ID
     * @param int $limit Number of items to return
     * @return Collection Collection of recommended items
     */
    public function getRecommendedItems(int $userId, int $limit = 8): Collection
    {
        // Get user's viewed or interacted items
        $userItems = Item::where('user_id', $userId)->pluck('id')->toArray();

        // If user has no items, return random items
        if (empty($userItems)) {
            return Item::with(['category', 'images'])
                ->where('status', '!=', 'taken')
                ->inRandomOrder()
                ->take($limit)
                ->get();
        }

        // Get similar items for each user item
        $recommendedItems = collect();
        foreach ($userItems as $itemId) {
            $similarItems = $this->getSimilarItems($itemId, 2);
            $recommendedItems = $recommendedItems->merge($similarItems);
        }

        // Remove duplicates and limit
        return $recommendedItems->unique('id')->take($limit);
    }
}