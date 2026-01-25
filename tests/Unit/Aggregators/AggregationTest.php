<?php

namespace Tests\Unit\Aggregators;

use App\Domains\Aggregation\Calculators\PerformanceCalculator;
use App\Domains\Aggregation\Calculators\PotentialCalculator;
use App\Models\Context;
use App\Models\Evidence;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class AggregationTest extends TestCase
{
    private PerformanceCalculator $perfCalculator;
    private PotentialCalculator $potCalculator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->perfCalculator = new PerformanceCalculator();
        $this->potCalculator = new PotentialCalculator();
    }

    public function test_performance_calculation_high_intensity_high_complexity(): void
    {
        $evidence = new Evidence([
            'type' => 'PERFORMANCE',
            'intensity' => 4,
        ]);
        $evidence->setRelation('context', new Context(['complexity_level' => 5]));

        $score = $this->perfCalculator->calculate(new Collection([$evidence]));

        // (4 * 5) / 20 * 100 = 100
        $this->assertEquals(100, $score);
    }

    public function test_performance_calculation_high_intensity_low_complexity(): void
    {
        $evidence = new Evidence([
            'type' => 'PERFORMANCE',
            'intensity' => 4,
        ]);
        $evidence->setRelation('context', new Context(['complexity_level' => 1]));

        $score = $this->perfCalculator->calculate(new Collection([$evidence]));

        // (4 * 1) / 20 * 100 = 20
        $this->assertEquals(20, $score);
    }

    public function test_potential_mixed_evidences_average(): void
    {
        // Ev1: High Perf at High Level -> 100
        $ev1 = new Evidence(['type' => 'POTENTIAL', 'intensity' => 4]);
        $ev1->setRelation('context', new Context(['complexity_level' => 5]));

        // Ev2: High Perf at Low Level -> 20
        $ev2 = new Evidence(['type' => 'POTENTIAL', 'intensity' => 4]);
        $ev2->setRelation('context', new Context(['complexity_level' => 1]));

        $score = $this->potCalculator->calculate(new Collection([$ev1, $ev2]));

        // Avg(100, 20) = 60
        $this->assertEquals(60, $score);
    }

    public function test_ignores_wrong_evidence_type(): void
    {
        $ev1 = new Evidence(['type' => 'POTENTIAL', 'intensity' => 4]);
        $ev1->setRelation('context', new Context(['complexity_level' => 5]));

        // Should return 0 because it strictly looks for 'PERFORMANCE'
        $score = $this->perfCalculator->calculate(new Collection([$ev1]));

        $this->assertEquals(0, $score);
    }

    public function test_returns_zero_when_collection_is_empty(): void
    {
        $this->assertEquals(0, $this->perfCalculator->calculate(new Collection([])));
        $this->assertEquals(0, $this->potCalculator->calculate(new Collection([])));
    }
}
