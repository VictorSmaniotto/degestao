<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Colaborador - {{ $person->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        @media print {
            @page {
                margin: 0.5cm;
            }

            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
                background: white;
            }

            .no-print {
                display: none !important;
            }

            .print-full {
                margin: 0 !important;
                padding: 0 !important;
                max-width: none !important;
                width: 100% !important;
                border: none !important;
                box-shadow: none !important;
                border-radius: 0 !important;
            }
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800 font-sans p-8 md:p-12 max-w-5xl mx-auto print-full">

    <!-- Header / Actions Removed as per user request (Auto-print handled by script) -->

    <!-- Main Card -->
    <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-200 print-full">

        <!-- Top Banner / User Info -->
        <div class="bg-slate-900 text-white p-8 flex items-center gap-6">
            <div
                class="w-20 h-20 rounded-full bg-blue-500 flex items-center justify-center text-2xl font-bold border-4 border-slate-700 shadow-lg">
                {{ strtoupper(substr($person->name, 0, 2)) }}
            </div>
            <div>
                <h1 class="text-3xl font-bold">{{ $person->name }}</h1>
                <p class="text-blue-200 text-lg">{{ $person->role }}</p>
                <div class="flex gap-4 mt-2 text-sm text-slate-400">
                    <span>{{ $person->department ?? 'Sem Departamento' }}</span>
                    <span>•</span>
                    <span>Gestor: {{ $person->manager?->name ?? 'N/A' }}</span>
                </div>
            </div>
            <div class="ml-auto text-right">
                <p class="text-xs uppercase tracking-widest text-slate-500">Ciclo de Avaliação</p>
                <p class="font-bold text-xl">{{ $cycle->name }}</p>
                <p class="text-sm text-slate-400">{{ $cycle->start_date->format('d/m/Y') }} -
                    {{ $cycle->end_date->format('d/m/Y') }}
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-8">

            <!-- Left Column: Metrics -->
            <div>
                <h3 class="text-lg font-bold border-b pb-2 mb-4 text-slate-700">Desempenho & Potencial</h3>

                <div class="bg-gray-50 rounded-xl p-6 border border-gray-200 mb-6 flex gap-4 items-center">
                    <!-- Mini Matrix -->
                    <div
                        class="grid grid-cols-3 gap-1 p-2 bg-white rounded shadow-sm border border-gray-200 w-24 h-24 shrink-0">
                        @for ($y = 3; $y >= 1; $y--)
                            @for ($x = 1; $x <= 3; $x++)
                                @php
                                    $active = ($position['potential'] == $y && $position['performance'] == $x);
                                    $color = match (true) {
                                        $y == 3 && $x == 3 => 'bg-green-600', // Star
                                        $y == 2 && $x == 1 => 'bg-orange-500', // Risk
                                        $y == 1 && $x == 1 => 'bg-red-600', // Under
                                        $y == 1 && $x == 3 => 'bg-blue-600', // Solid
                                        $active => 'bg-indigo-500',
                                        default => 'bg-gray-200'
                                    };
                                    // Override default matrix colors if active just to highlight
                                    // Or use specific logic. Comparisons logic:
                                    $bg = $active ? $color : 'bg-gray-100';
                                    $shadow = $active ? 'shadow-md border border-gray-400' : '';
                                @endphp
                                <div class="{{ $bg }} {{ $shadow }} rounded-sm relative"></div>
                            @endfor
                        @endfor
                    </div>

                    <div>
                        <p class="text-sm text-gray-500 mb-1">Posição Matriz 9-Box</p>
                        <p class="text-2xl font-bold text-slate-800">
                            {{ match (true) {
    $position['potential'] == 3 && $position['performance'] == 3 => 'Estrela',
    $position['potential'] == 1 && $position['performance'] == 1 => 'Insuficiente',
    $position['potential'] == 2 && $position['performance'] == 2 => 'Mantenedor',
    default => 'Em avaliação' // Placeholder simplificado
} }}
                        </p>
                        <p class="text-xs text-gray-400 mt-1">Eixo X: {{ number_format($result->x, 1) }}% | Eixo Y:
                            {{ number_format($result->y, 1) }}%
                        </p>
                    </div>
                </div>

                <h3 class="text-lg font-bold border-b pb-2 mb-4 text-slate-700">Histórico de Evidências</h3>
                <div class="space-y-4">
                    @forelse($evidences as $evidence)
                        <div class="flex gap-3 text-sm">
                            <div
                                class="mt-1 w-2 h-2 rounded-full shrink-0 {{ $evidence->type === 'positive' ? 'bg-green-500' : 'bg-red-500' }}">
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">{{ $evidence->description }}</p>
                                <p class="text-xs text-gray-500">{{ $evidence->occurred_at->format('d/m/Y') }} • Registrado
                                    por {{ $evidence->created_at->format('d/m') }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 italic">Nenhuma evidência registrada neste ciclo.</p>
                    @endforelse
                </div>

            </div>

            <!-- Right Column: Radar Chart -->
            <div>
                <h3 class="text-lg font-bold border-b pb-2 mb-4 text-slate-700">Radar de Competências</h3>
                <div class="relative h-64 md:h-80 w-full">
                    <canvas id="competencyRadar"></canvas>
                </div>
                <div class="bg-yellow-50 text-yellow-800 p-4 rounded-lg mt-6 text-sm border border-yellow-100">
                    <strong>Dica para Feedback:</strong> Use este gráfico para identificar gaps entre o esperado e o
                    realizado. Áreas "afundadas" no gráfico indicam pontos de atenção.
                </div>
            </div>

        </div>

        <!-- Footer -->
        <div class="bg-gray-50 p-6 text-center text-xs text-gray-400 border-t border-gray-200">
            Gerado em {{ now()->format('d/m/Y H:i') }} • Sistema de Gestão de Desempenho
        </div>
    </div>

    <script>
        const ctx = document.getElementById('competencyRadar');

        // Mock data logic - in production pass via PHP blade
        // As evidences have domains, we should aggregate them
        const data = {
            labels: ['Comportamental', 'Técnico', 'Entrega', 'Cultura', 'Liderança', 'Inovação'],
            datasets: [{
                label: 'Score do Colaborador',
                data: [{{ $result->getAverage() }}, {{ $result->x }}, {{ $result->y }}, 65, 55, 70], // Mock values mixed with real
                fill: true,
                backgroundColor: 'rgba(59, 130, 246, 0.2)',
                borderColor: 'rgb(59, 130, 246)',
                pointBackgroundColor: 'rgb(59, 130, 246)',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: 'rgb(59, 130, 246)'
            }]
        };

        new Chart(ctx, {
            type: 'radar',
            data: data,
            options: {
                elements: {
                    line: { borderWidth: 3 }
                },
                scales: {
                    r: {
                        angleLines: { display: true },
                        suggestedMin: 0,
                        suggestedMax: 100
                    }
                }
            }
        });

        // Auto-print when page loads
        window.onload = function () {
            setTimeout(() => {
                window.print();
            }, 500); // Small delay to ensure chart renders
        };
    </script>
</body>

</html>