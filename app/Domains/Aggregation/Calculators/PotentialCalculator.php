<?php

namespace App\Domains\Aggregation\Calculators;

use Illuminate\Support\Collection;

class PotentialCalculator
{
    private const MAX_INTENSITY = 4;
    private const MAX_COMPLEXITY = 5;

    public function calculate(Collection $evidences): int
    {
        $potentialEvidences = $evidences->where('type', 'POTENTIAL');

        if ($potentialEvidences->isEmpty()) {
            return 0;
        }

        $scores = $potentialEvidences->map(function ($evidence) {
            $intensity = $evidence->intensity; // 0-4
            $complexity = $evidence->context->complexity_level; // 1-5

            $rawScore = $intensity * $complexity;
            $maxScore = self::MAX_INTENSITY * self::MAX_COMPLEXITY;

            return ($rawScore / $maxScore) * 100;
        });

        // For Potential, we might consider "Max Observed" instead of Average?
        // User Story: "O 9BOX é derivado de evidências históricas".
        // If I showed High Potential ONCE, am I High Potential?
        // Usually Potential is about "Peak Capability Demonstrated".
        // However, consistency matters regardless. 
        // Let's stick to Average for now as defined in the "General Logic" thought process, 
        // to punish inconsistency (e.g. sometimes showing potential, sometimes failing).
        // BUT, usually you don't record "Low Potential". You just don't record Potential.
        // If someone records "Intensity 0" in Potential, it drags the score down.

        return (int) round($scores->average());
    }
}
