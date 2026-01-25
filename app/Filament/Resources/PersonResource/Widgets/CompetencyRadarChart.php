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
        // Se não houver record (ex: dashboard global), aborta ou exibe vazio.
        // Mas como será usado na página de EditRecord, $this->record deve estar populado
        if (!$this->record) {
            return [];
        }

        /** @var Person $person */
        $person = $this->record;

        // Buscamos todas as evidências, agrupando por Dimensão e Tipo
        // Para simplificar o Radar, vamos pegar a Média de Intensidade por Dimensão

        $evidences = $person->evidence()
            ->get()
            ->groupBy('dimension');

        $labels = [];
        $data = [];

        foreach ($evidences as $dimension => $items) {
            $labels[] = $dimension;
            $data[] = $items->avg('intensity');
        }

        return [
            'datasets' => [
                [
                    'label' => 'Média de Intensidade',
                    'data' => $data,
                    'fill' => true,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgb(54, 162, 235)',
                    'pointBackgroundColor' => 'rgb(54, 162, 235)',
                    'pointBorderColor' => '#fff',
                    'pointHoverBackgroundColor' => '#fff',
                    'pointHoverBorderColor' => 'rgb(54, 162, 235)',
                ],
            ],
            'labels' => $labels,
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
                    'max' => 4,
                    'ticks' => [
                        'stepSize' => 1,
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
