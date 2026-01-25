<?php

namespace App\Http\Controllers;

use App\Domains\Aggregation\Services\EvidenceAggregator;
use App\Models\Cycle;
use App\Models\Person;
use Illuminate\Http\Request;

class PersonProfileController extends Controller
{
    public function print(string $id)
    {
        $person = Person::with('manager')->findOrFail($id);

        // Use active cycle or latest
        $cycle = Cycle::active()->latest('start_date')->first();

        if (!$cycle) {
            abort(404, 'Nenhum ciclo ativo encontrado.');
        }

        $aggregator = app(EvidenceAggregator::class);
        $result = $aggregator->calculate($person, $cycle);

        $perfIndex = match (true) {
            $result->x <= 33 => 1,
            $result->x <= 66 => 2,
            default => 3,
        };

        $potIndex = match (true) {
            $result->y <= 33 => 1,
            $result->y <= 66 => 2,
            default => 3,
        };

        // Reuse category config from NineBoxMatrix (in a real app, this should be a shared service/config)
        // For now, hardcoding/replicating logic or we could extract it. 
        // Let's pass the raw indices and handle labels in the view or a shared helper.

        return view('reports.person-profile', [
            'person' => $person,
            'cycle' => $cycle,
            'result' => $result,
            'position' => [
                'performance' => $perfIndex,
                'potential' => $potIndex,
            ],
            // Pass evidences for context
            'evidences' => $person->evidence()->where('cycle_id', $cycle->id)->latest('occurred_at')->limit(10)->get(),
        ]);
    }
}
