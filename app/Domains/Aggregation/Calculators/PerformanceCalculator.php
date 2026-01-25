<?php

namespace App\Domains\Aggregation\Calculators;

use Illuminate\Support\Collection;

class PerformanceCalculator
{
    private const MAX_INTENSITY = 4;
    private const MAX_COMPLEXITY = 5;

    public function calculate(Collection $evidences): int
    {
        $performanceEvidences = $evidences->where('type', 'PERFORMANCE');

        if ($performanceEvidences->isEmpty()) {
            return 0;
        }

        $scores = $performanceEvidences->map(function ($evidence) {
            $intensity = $evidence->intensity; // 0-4
            $complexity = $evidence->context->complexity_level; // 1-5

            // Raw Score: 0 to 20
            $rawScore = $intensity * $complexity;

            // Max Possible Score: 20
            $maxScore = self::MAX_INTENSITY * self::MAX_COMPLEXITY;

            // Normalized to 0-100
            return ($rawScore / $maxScore) * 100;
        });

        // The result is the average of all demonstrated performances
        return (int) round($scores->average());
    }
}
