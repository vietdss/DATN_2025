<?php

namespace App\Services\Distance;

class Cosine
{
    public function distance(array $a, array $b): float
    {
        $dotProduct = 0.0;
        $normA = 0.0;
        $normB = 0.0;

        $length = count($a);
        for ($i = 0; $i < $length; $i++) {
            $dotProduct += $a[$i] * $b[$i];
            $normA += $a[$i] ** 2;
            $normB += $b[$i] ** 2;
        }

        if ($normA == 0.0 || $normB == 0.0) {
            return 1.0; // Nếu 1 vector rỗng => khoảng cách lớn nhất
        }

        $cosineSimilarity = $dotProduct / (sqrt($normA) * sqrt($normB));
        return 1.0 - $cosineSimilarity; // trả về khoảng cách (0 = giống nhau)
    }
}
