<?php

namespace Tests\Feature;

use App\Domains\Aggregation\Services\EvidenceAggregator;
use App\Models\Context;
use App\Models\Cycle;
use App\Models\Evidence;
use App\Models\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NineBoxIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_calculates_ninebox_from_database_records(): void
    {
        // 1. Arrange: Create World
        $cycle = Cycle::create([
            'name' => 'Q1 2025',
            'start_date' => now(),
            'end_date' => now()->addMonth(),
            'is_active' => true,
        ]);

        $lowContext = Context::create([
            'name' => 'Routine',
            'complexity_level' => 1,
            'is_structured' => true,
        ]);

        $highContext = Context::create([
            'name' => 'Crisis',
            'complexity_level' => 5,
            'is_structured' => false,
        ]);

        $person = Person::create([
            'name' => 'Alice Test',
            'email' => 'alice@test.com',
            'role' => 'Manager',
            'admitted_at' => now(),
        ]);

        // 2. Act: Insert Evidences via Eloquent
        // High Performance in High Context -> Should boost Perf score massively
        Evidence::create([
            'person_id' => $person->id,
            'cycle_id' => $cycle->id,
            'context_id' => $highContext->id,
            'type' => 'PERFORMANCE',
            'dimension' => 'Delivery',
            'intensity' => 4,
            'description' => 'Did great',
            'occurred_at' => now(),
        ]);

        // Low Potential in Low Context -> Should keep Pot score low
        Evidence::create([
            'person_id' => $person->id,
            'cycle_id' => $cycle->id,
            'context_id' => $lowContext->id,
            'type' => 'POTENTIAL',
            'dimension' => 'Learning',
            'intensity' => 2,
            'description' => 'Learned a bit',
            'occurred_at' => now(),
        ]);

        // 3. Assert: Run Aggregator
        /** @var EvidenceAggregator $aggregator */
        $aggregator = app(EvidenceAggregator::class);
        $result = $aggregator->calculate($person, $cycle);

        // Assert X (Performance)
        // (4 * 5) / 20 * 100 = 100
        $this->assertEquals(100, $result->x, 'Performance Score Mismatch');

        // Assert Y (Potential)
        // (2 * 1) / 20 * 100 = 10
        $this->assertEquals(10, $result->y, 'Potential Score Mismatch');

        // Assert Count
        $this->assertEquals(2, $result->evidenceCount);

        // Assert Quadrant Label exists
        $this->assertNotEmpty($result->quadrantLabel);
    }
}
