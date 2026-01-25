<?php

namespace Database\Seeders;

use App\Models\Context;
use App\Models\Cycle;
use App\Models\Evidence;
use App\Models\Person;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Database\Seeder;

class HistorySeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Cycles
        $cycleQ4 = Cycle::firstOrCreate([
            'name' => 'Q4 2025',
        ], [
            'start_date' => '2025-10-01',
            'end_date' => '2025-12-31',
            'is_active' => false,
        ]);

        $cycleQ1 = Cycle::firstOrCreate([
            'name' => 'Q1 2026',
        ], [
            'start_date' => '2026-01-01',
            'end_date' => '2026-03-31',
            'is_active' => true,
        ]);

        // 2. Get Person (Alice Star)
        $alice = Person::where('email', 'alice@co.com')->first();

        if (!$alice) {
            // Callback to main seeder if Alice doesn't exist
            $this->call(DatabaseSeeder::class);
            $alice = Person::where('email', 'alice@co.com')->firstOrFail();
        }

        // 3. Clear existing evidences for these cycles to ensure clean state
        Evidence::where('person_id', $alice->id)
            ->whereIn('cycle_id', [$cycleQ4->id, $cycleQ1->id])
            ->delete();

        // 4. Create History Trail
        $ctxRoutine = Context::first();

        // Q4 2025: Low Performance, Low Potential (Underperformer / Insuficiente) -> Pos 1, 1
        $this->createEvidence($alice, $cycleQ4, $ctxRoutine, 'PERFORMANCE', 1);
        $this->createEvidence($alice, $cycleQ4, $ctxRoutine, 'POTENTIAL', 1);

        // Q1 2026: High Performance, High Potential (Star / Estrela) -> Pos 3, 3
        $this->createEvidence($alice, $cycleQ1, $ctxRoutine, 'PERFORMANCE', 4); // High
        $this->createEvidence($alice, $cycleQ1, $ctxRoutine, 'POTENTIAL', 4); // High

        $this->command->info("History Trail created for Alice Star: Q4 (Insuficiente) -> Q1 (Estrela)");
    }

    private function createEvidence($person, $cycle, $context, $type, $intensity)
    {
        Evidence::create([
            'person_id' => $person->id,
            'cycle_id' => $cycle->id,
            'context_id' => $context->id,
            'type' => $type,
            'dimension' => 'Geral',
            'intensity' => $intensity,
            'description' => 'History Seeder generated data.',
            'occurred_at' => $cycle->start_date, // Just a date within cycle
        ]);
    }
}
