<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CohereService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = env('COHERE_API_KEY');
    }

    public function rerank(string $query, array $documents): array
    {
        $texts = array_column($documents, 'text');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->post('https://api.cohere.ai/v2/rerank', [
            'model' => 'rerank-v3.5',
            'query' => $query,
            'documents' => $texts,
        ]);

        if ($response->failed()) {
            throw new \Exception("Cohere API failed: " . json_encode($response->json()));
        }

        $results = $response->json('results');

        return collect($results)->map(function ($result) use ($documents) {
            $doc = $documents[$result['index']];
            return [
                'id' => $doc['id'],
                'text' => $doc['text'],
                'relevance_score' => $result['relevance_score'],
            ];
        })->sortByDesc('relevance_score')->values()->all();
    }
}
