<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class NineBoxMatrix extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.nine-box-matrix';

    protected static ?string $title = 'Matriz 9BOX';
    protected static ?string $navigationLabel = 'Matriz 9BOX';
    protected static ?string $slug = 'nine-box';

    public ?string $cycleId = null;
    public ?string $selectedPersonId = null;
    public ?array $selectedPerson = null;

    public function mount(): void
    {
        // Default to latest active cycle
        $this->cycleId = \App\Models\Cycle::active()->latest('start_date')->first()?->id;
    }

    public function selectPerson(string $id): void
    {
        $this->selectedPersonId = $id;
        // Find the person in the already calculated matrix data to avoid re-querying everything if possible, 
        // or just re-fetch for simplicity and data freshness.
        // Let's re-fetch the specific person details needed for the modal.

        $person = \App\Models\Person::with('manager')->find($id);

        if (!$person || !$this->cycleId) {
            return;
        }

        $cycle = \App\Models\Cycle::find($this->cycleId);
        $aggregator = app(\App\Domains\Aggregation\Services\EvidenceAggregator::class);
        $result = $aggregator->calculate($person, $cycle);

        // Calculate position
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

        $xLegacy = $perfIndex - 1;
        $yLegacy = $potIndex - 1;
        $quadrantKey = "{$xLegacy}-{$yLegacy}";
        $quadrants = $this->getQuadrants();
        $config = $quadrants[$quadrantKey] ?? $quadrants['1-1'];

        $this->selectedPerson = [
            'id' => $person->id,
            'name' => $person->name,
            'role' => $person->role,
            'department' => $person->department,
            'manager_name' => $person->manager?->name,
            'admitted_at' => $person->admitted_at?->translatedFormat('d \d\e F \d\e Y'),
            'evaluation_date' => now()->translatedFormat('d \d\e F \d\e Y'), // Mock for now, or use Cycle end date
            'avatar' => null,
            'position' => [
                'performance' => $perfIndex, // 1-3
                'potential' => $potIndex, // 1-3
            ],
            'category' => $config,
        ];
    }

    public function closeModal(): void
    {
        $this->selectedPersonId = null;
        $this->selectedPerson = null;
    }

    public function getViewData(): array
    {
        return [
            'cycles' => \App\Models\Cycle::orderByDesc('start_date')->pluck('name', 'id'),
            'matrix' => $this->calculateMatrix(),
        ];
    }

    private function calculateMatrix(): array
    {
        if (!$this->cycleId) {
            return [];
        }

        $cycle = \App\Models\Cycle::find($this->cycleId);
        $people = \App\Models\Person::all();
        $aggregator = app(\App\Domains\Aggregation\Services\EvidenceAggregator::class);
        $quadrants = $this->getQuadrants();

        $employees = [];

        foreach ($people as $person) {
            $result = $aggregator->calculate($person, $cycle);

            // Map 0-100 score to 1-3 index for MiniMatrix
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

            // Map indices (1-3) to legacy Quadrant Keys (0-2)
            $xLegacy = $perfIndex - 1;
            $yLegacy = $potIndex - 1;
            $quadrantKey = "{$xLegacy}-{$yLegacy}";

            // Fallback to Mid-Mid if config missing, though should cover all cases
            $config = $quadrants[$quadrantKey] ?? $quadrants['1-1'];

            $employees[] = [
                'id' => $person->id,
                'name' => $person->name,
                'role' => $person->role,
                'avatar' => null, // Person model has no avatar column yet
                'email' => $person->email,
                'x' => $result->x,
                'y' => $result->y,
                'position' => [
                    'performance' => $perfIndex, // 1-3
                    'potential' => $potIndex, // 1-3
                ],
                'category' => [
                    'label' => $config['label'],
                    'color' => $config['color'],
                    'description' => $config['description'],
                ]
            ];
        }

        return $employees;
    }

    public function getQuadrants(): array
    {
        return [
            // Row 3 (Top) - High Potential (y=2)
            '0-2' => [
                'label' => 'Enigma',
                'description' => 'Baixo desempenho e alto potencial. Pode não ter as habilidades corretas para o cargo.',
                'recommendation' => 'Investir em treinamento e mentoria para adequação ao cargo ou realocação.',
                'color' => 'bg-[#A78BFA] text-white', // Purple 400
                'icon' => 'heroicon-o-question-mark-circle',
            ],
            '1-2' => [
                'label' => 'Forte Desempenho',
                'description' => 'Colaborador com alto impacto e em crescimento profissional.',
                'recommendation' => 'Oferecer novos desafios e oportunidades de liderança em projetos.',
                'color' => 'bg-[#5B21B6] text-white', // Violet 800
                'icon' => 'heroicon-o-arrow-trending-up',
            ],
            '2-2' => [
                'label' => 'Alto Potencial',
                'description' => 'Alto desempenho e alto potencial. Indica que está pronto para novos desafios.',
                'recommendation' => 'Preparar para sucessão e posições estratégicas.',
                'color' => 'bg-[#1E1B4B] text-white', // Indigo 950
                'icon' => 'heroicon-o-star',
            ],

            // Row 2 (Mid) - Mid Potential (y=1)
            '0-1' => [
                'label' => 'Questionável',
                'description' => 'Potencial moderado. Vale avaliar se o cargo e a área são ideais para seu perfil.',
                'recommendation' => 'Avaliar adequação à função e fornecer feedback claro sobre expectativas.',
                'color' => 'bg-[#FB7185] text-white', // Rose 400
                'icon' => 'heroicon-o-exclamation-triangle',
            ],
            '1-1' => [
                'label' => 'Mantenedor',
                'description' => 'Colaborador performa bem mas pode ser desafiado. A maioria dos colaboradores deve estar aqui.',
                'recommendation' => 'Manter motivado e reconhecer a consistência na entrega.',
                'color' => 'bg-[#8B5CF6] text-white', // Violet 500
                'icon' => 'heroicon-o-scale',
            ],
            '2-1' => [
                'label' => 'Forte Desempenho',
                'description' => 'Alto desempenho, mas pode haver espaço para crescer na posição atual.',
                'recommendation' => 'Incentivar o desenvolvimento de novas competências.',
                'color' => 'bg-[#4C1D95] text-white', // Violet 900
                'icon' => 'heroicon-o-check-badge',
            ],

            // Row 1 (Bottom) - Low Potential (y=0)
            '0-0' => [
                'label' => 'Insuficiente',
                'description' => 'Baixo desempenho e baixo potencial.',
                'recommendation' => 'Iniciar plano de recuperação ou considerar desligamento.',
                'color' => 'bg-[#450a0a] text-white', // Red 950
                'icon' => 'heroicon-o-x-circle',
            ],
            '1-0' => [
                'label' => 'Eficaz',
                'description' => 'Desempenho esperado. Pode não estar pronto para maiores responsabilidades.',
                'recommendation' => 'Monitorar desempenho e oferecer suporte pontual.',
                'color' => 'bg-[#FB7185] text-white', // Rose 400 
                'icon' => 'heroicon-o-hand-thumb-up',
            ],
            '2-0' => [
                'label' => 'Comprometido',
                'description' => 'Alto desempenho, mas pode não estar pronto para maiores responsabilidades.',
                'recommendation' => 'Reconhecer a dedicação e manter o engajamento.',
                'color' => 'bg-[#818CF8] text-white', // Indigo 400
                'icon' => 'heroicon-o-shield-check',
            ],
        ];
    }

    public function updatedCycleId(): void
    {
        // Livewire auto-refresh
    }
}
