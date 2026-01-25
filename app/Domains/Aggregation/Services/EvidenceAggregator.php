<?php

namespace App\Domains\Aggregation\Services;

use App\Domains\Aggregation\Calculators\PerformanceCalculator;
use App\Domains\Aggregation\Calculators\PotentialCalculator;
use App\Domains\Aggregation\DTOs\AxesResult;
use App\Models\Cycle;
use App\Models\Person;

class EvidenceAggregator
{
    public function __construct(
        protected PerformanceCalculator $performanceCalculator,
        protected PotentialCalculator $potentialCalculator,
    ) {
    }

    public function calculate(Person $person, Cycle $cycle): AxesResult
    {
        // Load evidences with context for calculations
        // We use the relationship defined in Person model
        $evidences = $person->evidence()
            ->where('cycle_id', $cycle->id)
            ->with('context') // Eager load context for complexity
            ->get();

        $x = $this->performanceCalculator->calculate($evidences);
        $y = $this->potentialCalculator->calculate($evidences);

        return new AxesResult(
            x: $x,
            y: $y,
            evidenceCount: $evidences->count(),
            quadrantLabel: $this->determineQuadrant($x, $y)
        );
    }

    private function determineQuadrant(int $x, int $y): string
    {
        // Simple 3x3 grid logic
        // Boundaries: 33, 66

        $xLevel = match (true) {
            $x <= 33 => 'Low',
            $x <= 66 => 'Mid',
            default => 'High',
        };

        $yLevel = match (true) {
            $y <= 33 => 'Low',
            $y <= 66 => 'Mid',
            default => 'High',
        };

        return "{$xLevel} Perf / {$yLevel} Pot";
    }
}
