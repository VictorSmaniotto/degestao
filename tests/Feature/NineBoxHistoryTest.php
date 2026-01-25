<?php

namespace Tests\Feature;

use App\Filament\Pages\NineBoxMatrix;
use App\Models\Context;
use App\Models\Cycle;
use App\Models\Evidence;
use App\Models\Person;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class NineBoxHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_calculates_previous_position_when_history_exists(): void
    {
        // 1. Setup Data
        $user = User::factory()->create(['role' => 'admin']); // Admin to see all

        // Past Cycle
        $cycleQ1 = Cycle::create([
            'name' => 'Q1 Past',
            'start_date' => now()->subMonths(6),
            'end_date' => now()->subMonths(4),
            'is_active' => false,
        ]);

        // Current Cycle
        $cycleQ2 = Cycle::create([
            'name' => 'Q2 Current',
            'start_date' => now()->subMonths(3),
            'end_date' => now()->subMonth(), // Ended recently
            'is_active' => true,
        ]);

        $context = Context::create(['name' => 'General', 'is_structured' => true, 'complexity_level' => 3]);

        $person = Person::create([
            'name' => 'Evolution Man',
            'email' => 'evo@test.com',
            'role' => 'Tester',
            'admitted_at' => now()->subYear(),
        ]);

        // 2. Create Evidences

        // Q1: Low/Low (Pos 1,1)
        // Intensity 1 should result in low score
        Evidence::create([
            'person_id' => $person->id,
            'cycle_id' => $cycleQ1->id,
            'context_id' => $context->id,
            'type' => 'PERFORMANCE',
            'intensity' => 1,
            'description' => 'Low Perf',
            'dimension' => 'Geral',
            'occurred_at' => now(),
        ]);
        Evidence::create([
            'person_id' => $person->id,
            'cycle_id' => $cycleQ1->id,
            'context_id' => $context->id,
            'type' => 'POTENTIAL',
            'intensity' => 1,
            'description' => 'Low Pot',
            'dimension' => 'Geral',
            'occurred_at' => now(),
        ]);

        // Q2: High/High (Pos 3,3)
        // Intensity 5 (Max) to ensure it hits > 66%
        Evidence::create([
            'person_id' => $person->id,
            'cycle_id' => $cycleQ2->id,
            'context_id' => $context->id,
            'type' => 'PERFORMANCE',
            'intensity' => 5,
            'description' => 'Super High Perf',
            'dimension' => 'Geral',
            'occurred_at' => now(),
        ]);
        Evidence::create([
            'person_id' => $person->id,
            'cycle_id' => $cycleQ2->id,
            'context_id' => $context->id,
            'type' => 'POTENTIAL',
            'intensity' => 5,
            'description' => 'Super High Pot',
            'dimension' => 'Geral',
            'occurred_at' => now(),
        ]);

        // 3. Test Logic
        $component = Livewire::actingAs($user)
            ->test(NineBoxMatrix::class)
            ->set('cycleId', $cycleQ2->id);

        $viewData = $component->viewData('matrix');

        // Find our person in the matrix results
        $employeeData = collect($viewData)->firstWhere('id', $person->id);

        // Assert Current Position is 3,3 (High)
        $this->assertEquals(3, $employeeData['position']['performance']);
        $this->assertEquals(3, $employeeData['position']['potential']);

        // Assert Previous Position is 1,1 (Low) - This proves history logic works
        $this->assertNotNull($employeeData['previous_position']);
        $this->assertEquals(1, $employeeData['previous_position']['performance']);
        $this->assertEquals(1, $employeeData['previous_position']['potential']);
    }

    public function test_shows_no_previous_position_if_no_history_available(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $cycle = Cycle::create([
            'name' => 'Q1 Only',
            'start_date' => now(),
            'end_date' => now()->addMonths(3),
            'is_active' => true,
        ]);

        $person = Person::create(['name' => 'Newbie', 'email' => 'new@test.com', 'role' => 'Intern', 'admitted_at' => now()]);

        // Create evidence just to have position
        $context = Context::create(['name' => 'General', 'is_structured' => true, 'complexity_level' => 3]);
        Evidence::create([
            'person_id' => $person->id,
            'cycle_id' => $cycle->id,
            'context_id' => $context->id,
            'type' => 'PERFORMANCE',
            'intensity' => 3,
            'description' => 'Mid',
            'dimension' => 'Geral',
            'occurred_at' => now(),
        ]);
        Evidence::create([
            'person_id' => $person->id,
            'cycle_id' => $cycle->id,
            'context_id' => $context->id,
            'type' => 'POTENTIAL',
            'intensity' => 3,
            'description' => 'Mid',
            'dimension' => 'Geral',
            'occurred_at' => now(),
        ]);

        $component = Livewire::actingAs($user)
            ->test(NineBoxMatrix::class)
            ->set('cycleId', $cycle->id);

        $viewData = $component->viewData('matrix');
        $employeeData = collect($viewData)->firstWhere('id', $person->id);

        $this->assertNull($employeeData['previous_position']);
    }
}
