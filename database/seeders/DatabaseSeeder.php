<?php

namespace Database\Seeders;

use App\Models\Context;
use App\Models\Cycle;
use App\Models\Evidence;
use App\Models\Person;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin User
        User::factory()->create([
            'name' => 'Victor Admin',
            'email' => 'admin@degestao.com',
            'password' => bcrypt('password'),
        ]);

        // 2. Cycle
        $cycle = Cycle::create([
            'name' => 'Q1 2025',
            'start_date' => now()->startOfQuarter(),
            'end_date' => now()->endOfQuarter(),
            'is_active' => true,
        ]);

        // 3. Contexts
        $ctxRoutine = Context::create([
            'name' => 'Manutenção de Rotina',
            'complexity_level' => 1, // Low
            'is_structured' => true,
        ]);

        $ctxProject = Context::create([
            'name' => 'Novo Projeto Estratégico',
            'complexity_level' => 3, // Mid
            'is_structured' => true,
        ]);

        $ctxCrisis = Context::create([
            'name' => 'Gestão de Crise (War Room)',
            'complexity_level' => 5, // High
            'is_structured' => false,
        ]);

        // 4. People & Evidences

        // Scenario A: High Perf / High Pot (The Star)
        // Works well in High Complexity
        $alice = Person::create([
            'name' => 'Alice Star',
            'email' => 'alice@co.com',
            'role' => 'Tech Lead',
            'department' => 'Tecnologia',
            'admitted_at' => now()->subYears(3)
        ]);
        $this->createEvidence($alice, $cycle, $ctxCrisis, 'PERFORMANCE', 4);
        $this->createEvidence($alice, $cycle, $ctxCrisis, 'POTENTIAL', 4);

        // Scenario B: High Perf / Low Pot (The Expert/Professional)
        // Delivers great results in Routine, but struggles with Ambiguity (Potential)
        $bob = Person::create([
            'name' => 'Bob Builder',
            'email' => 'bob@co.com',
            'role' => 'Senior Dev',
            'department' => 'Tecnologia',
            'manager_id' => $alice->id,
            'admitted_at' => now()->subYears(5)
        ]);
        $this->createEvidence($bob, $cycle, $ctxRoutine, 'PERFORMANCE', 4); // Great delivery
        $this->createEvidence($bob, $cycle, $ctxCrisis, 'POTENTIAL', 1); // Struggles in crisis

        // Scenario C: Low Perf / High Pot (The Rough Diamond)
        // New hire, struggling with routine but amazing insightful ideas in crisis
        $charlie = Person::create([
            'name' => 'Charlie Spark',
            'email' => 'charlie@co.com',
            'role' => 'Junior Dev',
            'department' => 'Tecnologia',
            'manager_id' => $bob->id,
            'admitted_at' => now()->subMonths(3)
        ]);
        $this->createEvidence($charlie, $cycle, $ctxRoutine, 'PERFORMANCE', 1); // Learning curve
        $this->createEvidence($charlie, $cycle, $ctxCrisis, 'POTENTIAL', 4); // Amazing potential shown

        // Scenario D: Mid / Mid (Core Player)
        $david = Person::create([
            'name' => 'David Core',
            'email' => 'david@co.com',
            'role' => 'Mid Dev',
            'department' => 'Produto',
            'manager_id' => $alice->id,
            'admitted_at' => now()->subYear()
        ]);
        $this->createEvidence($david, $cycle, $ctxProject, 'PERFORMANCE', 3);
        $this->createEvidence($david, $cycle, $ctxProject, 'POTENTIAL', 3);

        // Scenario E: Low / Low (Underperformer)
        $eve = Person::create([
            'name' => 'Eve Struggler',
            'email' => 'eve@co.com',
            'role' => 'Intern',
            'department' => 'Recursos Humanos',
            'manager_id' => $david->id,
            'admitted_at' => now()->subMonth()
        ]);
        $this->createEvidence($eve, $cycle, $ctxRoutine, 'PERFORMANCE', 1);
        $this->createEvidence($eve, $cycle, $ctxRoutine, 'POTENTIAL', 1);
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
            'description' => 'Seed generated evidence.',
            'occurred_at' => now(),
        ]);
    }
}
