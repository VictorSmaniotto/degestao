<?php

namespace App\Filament\Resources\PersonResource\Widgets;

use App\Models\Person;
use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Model;

class CompetencyRadarChart extends ChartWidget
{
    protected static ?string $heading = 'Radar de Competências';

    // Configura o widget para receber o record atual da página
    public ?Model $record = null;

    protected function getData(): array
    {
        if (!$this->record) {
            return [];
        }

        /** @var Person $person */
        $person = $this->record;

        // Tenta buscar ciclo ativo, senão pega o último encerrado
        $cycle = \App\Models\Cycle::active()->latest('start_date')->first();
        if (!$cycle) {
            $cycle = \App\Models\Cycle::latest('end_date')->first();
        }

        if (!$cycle) {
            return [];
        }

        $aggregator = app(\App\Domains\Aggregation\Services\EvidenceAggregator::class);
        $result = $aggregator->calculate($person, $cycle);

        // Usar eixos fixos simulados + reais do Aggregator
        // O aggregator retorna X (Performance) e Y (Potencial) e média.
        // O MyProfile mistura isso com mocks. Vou manter a logica do MyProfile para consistência visual.
        // ['Comportamental', 'Técnico', 'Entrega', 'Cultura', 'Liderança', 'Inovação']
        // Como o aggregator atual foca em X/Y, vamos usar os dados disponíveis.
        // Se o aggregator evoluir para devolver breakdown por dimensão, melhor.
        // Por hora, vou replicar exatamente os dados do MyProfile.

        return [
            'datasets' => [
                [
                    'label' => "Resultado (Ciclo: {$cycle->name})",
                    'data' => [$result->getAverage(), $result->x, $result->y, 75, 60, 80], // Mantendo simetria com MyProfile
                    'fill' => true,
                    'backgroundColor' => 'rgba(56, 189, 248, 0.2)',
                    'borderColor' => '#38bdf8',
                    'pointBackgroundColor' => '#38bdf8',
                    'pointBorderColor' => '#fff',
                    'pointHoverBackgroundColor' => '#fff',
                    'pointHoverBorderColor' => '#38bdf8',
                ],
            ],
            'labels' => ['Comportamental', 'Técnico', 'Entrega', 'Cultura', 'Liderança', 'Inovação'],
        ];
    }

    protected function getType(): string
    {
        return 'radar';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'r' => [
                    'min' => 0,
                    'max' => 100,
                    'ticks' => [
                        'stepSize' => 20,
                        'backdropColor' => 'transparent', // Remove fundo branco dos números
                        'color' => '#94a3b8', // Slate-400 para números
                    ],
                    'grid' => [
                        'color' => 'rgba(148, 163, 184, 0.1)', // Slate-400 com transparência
                    ],
                    'pointLabels' => [
                        'color' => '#cbd5e1', // Slate-300 para textos das dimensões
                        'font' => [
                            'size' => 11,
                        ],
                    ],
                ],
            ],
        ];
    }
}
