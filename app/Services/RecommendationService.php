<?php
namespace App\Services;

use App\Models\Item;
use Illuminate\Support\Collection;
use Phpml\FeatureExtraction\TfIdfTransformer;
use Phpml\FeatureExtraction\TokenCountVectorizer;
use Phpml\Tokenization\WhitespaceTokenizer;
use Phpml\Math\Distance\Euclidean;

class RecommendationService
{
    public function getSimilarItems(int $itemId, int $k = 4): Collection
    {
        $targetItem = Item::findOrFail($itemId);
        $items = Item::where('id', '!=', $itemId)
            ->where('status', '!=', 'taken')
            ->get();

        if ($items->isEmpty()) {
            return collect();
        }

        $corpus = [];
        $itemMap = [];

        // Tạo corpus văn bản
        foreach ($items as $item) {
            $corpus[] = $item->title . ' ' . $item->description;
            $itemMap[] = $item;
        }

        // Vector hóa corpus
        $vectorizer = new TokenCountVectorizer(new WhitespaceTokenizer());
        $vectorizer->fit($corpus);
        $vectorizer->transform($corpus);

        $tfIdfTransformer = new TfIdfTransformer();
        $tfIdfTransformer->fit($corpus);
        $tfIdfTransformer->transform($corpus);

        // Vector hóa target
        $targetText = [$targetItem->title . ' ' . $targetItem->description];
        $vectorizer->transform($targetText);
        $tfIdfTransformer->transform($targetText);

        // Tính khoảng cách Euclidean
        $euclidean = new Euclidean();
        $distances = [];

        foreach ($corpus as $index => $vec) {
            $distances[] = [
                'item' => $itemMap[$index],
                'distance' => $euclidean->distance($targetText[0], $vec),
            ];
        }

        // Sắp xếp tăng dần theo khoảng cách
        usort($distances, fn($a, $b) => $a['distance'] <=> $b['distance']);

        // Lấy top K
        $topItems = array_slice(array_column($distances, 'item'), 0, $k);

        return collect($topItems);
    }
}
