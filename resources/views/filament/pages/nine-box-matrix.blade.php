<x-filament-panels::page>
    @php
        // 9-Box Category mappings with exact colors from reference (dark theme)
        $categoryConfig = [
            'star' => [
                'label' => 'Estrela',
                'color' => 'hsl(142 70% 50%)',
                'bgColor' => 'hsl(142 70% 15%)',
                'description' => 'Alto potencial combinado com excelente desempenho',
                'recommendation' => 'Preparar para posições de liderança e projetos estratégicos'
            ],
            'high-performer' => [
                'label' => 'Alto Desempenho',
                'color' => 'hsl(142 55% 55%)',
                'bgColor' => 'hsl(142 55% 15%)',
                'description' => 'Entrega consistente com potencial moderado de crescimento',
                'recommendation' => 'Manter engajado com desafios e reconhecimento'
            ],
            'high-potential' => [
                'label' => 'Alto Potencial',
                'color' => 'hsl(199 80% 55%)',
                'bgColor' => 'hsl(199 80% 15%)',
                'description' => 'Grande potencial de crescimento com desempenho em desenvolvimento',
                'recommendation' => 'Investir em desenvolvimento e mentoria'
            ],
            'core-player' => [
                'label' => 'Mantenedor',
                'color' => 'hsl(262 75% 65%)',
                'bgColor' => 'hsl(262 75% 15%)',
                'description' => 'Desempenho e potencial equilibrados',
                'recommendation' => 'Desenvolver habilidades específicas para crescimento'
            ],
            'solid-performer' => [
                'label' => 'Profissional Eficaz',
                'color' => 'hsl(220 65% 60%)',
                'bgColor' => 'hsl(220 65% 15%)',
                'description' => 'Desempenho sólido com foco na função atual',
                'recommendation' => 'Valorizar expertise e considerar como referência técnica'
            ],
            'inconsistent' => [
                'label' => 'Inconsistente',
                'color' => 'hsl(45 85% 55%)',
                'bgColor' => 'hsl(45 85% 15%)',
                'description' => 'Alto potencial mas desempenho abaixo do esperado',
                'recommendation' => 'Investigar barreiras e oferecer suporte direcionado'
            ],
            'risk' => [
                'label' => 'Questionável',
                'color' => 'hsl(25 90% 58%)',
                'bgColor' => 'hsl(25 90% 15%)',
                'description' => 'Desempenho moderado com potencial limitado',
                'recommendation' => 'Avaliar adequação à função e plano de melhoria'
            ],
            'underperformer' => [
                'label' => 'Insuficiente',
                'color' => 'hsl(0 75% 55%)',
                'bgColor' => 'hsl(0 75% 15%)',
                'description' => 'Desempenho e potencial abaixo do esperado',
                'recommendation' => 'Plano de melhoria urgente ou realocação'
            ],
            'enigma' => [
                'label' => 'Enigma',
                'color' => 'hsl(280 65% 60%)',
                'bgColor' => 'hsl(280 65% 15%)',
                'description' => 'Potencial moderado mas desempenho inconsistente',
                'recommendation' => 'Compreender motivações e realinhar expectativas'
            ],
        ];

        // Function to get category key from position
        function getCategoryKey($position)
        {
            $perf = $position['performance'];
            $pot = $position['potential'];

            if ($pot === 3 && $perf === 3)
                return 'star';
            if ($pot === 2 && $perf === 3)
                return 'high-performer';
            if ($pot === 3 && $perf === 2)
                return 'high-potential';
            if ($pot === 2 && $perf === 2)
                return 'core-player';
            if ($pot === 1 && $perf === 3)
                return 'solid-performer';
            if ($pot === 3 && $perf === 1)
                return 'inconsistent';
            if ($pot === 1 && $perf === 2)
                return 'risk';
            if ($pot === 1 && $perf === 1)
                return 'underperformer';
            return 'enigma'; // pot === 2 && perf === 1
        }
    @endphp

    <!-- Header Controls -->
    <div class="flex flex-col md:flex-row gap-4 mb-6 items-center justify-between"
        style="background: hsl(220 25% 12%); border: 1px solid hsl(220 20% 20%); padding: 1rem; border-radius: 0.75rem;">
        <div class="w-full md:w-1/3">
            <div class="relative">
                <x-heroicon-o-magnifying-glass class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
                <input type="text" placeholder="Buscar colaborador..." class="pl-10 w-full rounded-lg text-sm"
                    style="background: hsl(220 20% 16%); border: 1px solid hsl(220 20% 20%); color: hsl(220 15% 95%); padding: 0.5rem 0.75rem 0.5rem 2.5rem;" />
            </div>
        </div>

        <div class="flex gap-4 w-full md:w-auto">
            <select wire:model.live="cycleId" class="rounded-lg text-sm py-2 pl-3 pr-10"
                style="background: hsl(220 20% 16%); border: 1px solid hsl(220 20% 20%); color: hsl(220 15% 95%);">
                <option value="">Selecione o Ciclo...</option>
                @foreach($cycles as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>

            <button class="px-4 py-2 text-sm font-medium rounded-lg"
                style="background: hsl(220 20% 18%); border: 1px solid hsl(220 20% 20%); color: hsl(220 15% 90%);">
                Filtros
            </button>
        </div>
    </div>

    @if($cycleId)
        <div class="mb-4 text-sm font-medium flex items-center gap-2" style="color: hsl(220 10% 60%);">
            <x-heroicon-o-users class="w-4 h-4" />
            {{ count($matrix) }} colaboradores
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-4">
            @foreach($matrix as $employee)
                @php
                    $catKey = getCategoryKey($employee['position']);
                    $cat = $categoryConfig[$catKey];
                @endphp
                <div wire:click="selectPerson('{{ $employee['id'] }}')"
                    class="group cursor-pointer transition-all duration-300 hover:-translate-y-1 hover:shadow-lg"
                    style="background: hsl(220 25% 12%); border: 1px solid hsl(220 20% 20%); border-radius: 0.75rem; padding: 1.25rem;">

                    <div class="flex items-center gap-4">
                        <!-- Avatar -->
                        <div class="w-12 h-12 rounded-full flex items-center justify-center text-sm font-semibold shrink-0 ring-2 shadow-md"
                            style="background: hsl(220 70% 55% / 0.1); color: hsl(220 70% 55%); ring-color: hsl(220 25% 8%);">
                            {{ strtoupper(substr($employee['name'], 0, 2)) }}
                        </div>

                        <!-- Info -->
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold truncate transition-colors group-hover:text-blue-400"
                                style="color: hsl(220 15% 95%);">
                                {{ $employee['name'] }}
                            </h3>
                            <p class="text-sm truncate" style="color: hsl(220 10% 60%);">{{ $employee['role'] }}</p>
                            <p class="text-xs mt-0.5 truncate" style="color: hsl(220 10% 45%);">
                                {{ $employee['department'] ?? 'Sem departamento' }}
                            </p>
                        </div>

                        <!-- Status Dot + Mini Matrix -->
                        <div class="shrink-0" style="display: flex; flex-direction: row; align-items: center; gap: 0.5rem;">
                            <!-- Mini Matrix -->
                            <div
                                style="display: inline-grid; grid-template-columns: repeat(3, 1fr); gap: 2px; padding: 4px; background: hsl(220 20% 16%); border-radius: 4px;">
                                @for ($pot = 3; $pot >= 1; $pot--)
                                    @for ($perf = 1; $perf <= 3; $perf++)
                                        @php
                                            $isActive = ($employee['position']['potential'] == $pot && $employee['position']['performance'] == $perf);
                                            $cellCatKey = getCategoryKey(['potential' => $pot, 'performance' => $perf]);
                                            $cellColor = $isActive ? $categoryConfig[$cellCatKey]['color'] : 'hsl(220 20% 25%)';
                                        @endphp
                                        <div
                                            style="width: 14px; height: 14px; border-radius: 2px; background: {{ $cellColor }}; {{ $isActive ? 'box-shadow: 0 0 6px ' . $cellColor . ';' : '' }}">
                                        </div>
                                    @endfor
                                @endfor
                            </div>
                            <!-- Colored Status Dot -->
                            <div style="width: 10px; height: 10px; border-radius: 50%; background: {{ $cat['color'] }};"></div>
                        </div>
                    </div>

                    <!-- Footer: Category Badge -->
                    <div class="mt-4 pt-4 flex items-center justify-between gap-3"
                        style="border-top: 1px solid hsl(220 20% 20%);">
                        <span class="inline-flex items-center font-semibold rounded-full px-2 py-0.5 text-xs"
                            style="background: {{ $cat['bgColor'] }}; color: {{ $cat['color'] }};">
                            {{ $cat['label'] }}
                        </span>
                        <span class="text-xs text-right line-clamp-1" style="color: hsl(220 10% 60%);">
                            {{ Str::limit($cat['description'], 35) }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="flex flex-col items-center justify-center py-20 rounded-xl"
            style="background: hsl(220 25% 12%); border: 1px dashed hsl(220 20% 25%);">
            <x-heroicon-o-chart-bar class="w-16 h-16 mb-4" style="color: hsl(220 10% 40%);" />
            <h3 class="text-lg font-medium" style="color: hsl(220 15% 95%);">Nenhum ciclo selecionado</h3>
            <p class="text-sm mt-1" style="color: hsl(220 10% 60%);">Selecione um ciclo para visualizar a matriz</p>
        </div>
    @endif

    <!-- MODAL OVERLAY -->
    @if($selectedPerson)
        @php
            $modalCatKey = getCategoryKey($selectedPerson['position']);
            $modalCat = $categoryConfig[$modalCatKey];
        @endphp
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6" x-data
            x-on:keydown.escape.window="$wire.closeModal()">

            <!-- Backdrop -->
            <div class="absolute inset-0 bg-black/80 backdrop-blur-sm transition-opacity" wire:click="closeModal"></div>

            <!-- Modal Container -->
            <div class="relative w-full max-w-lg overflow-hidden transform transition-all animate-scale-in"
                style="background: hsl(220 25% 12%); border: 1px solid hsl(220 20% 20%); border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);">

                <!-- Close Button -->
                <button wire:click="closeModal"
                    class="transition-colors z-10 hover:text-white p-1 rounded-full hover:bg-white/10"
                    style="position: absolute; top: 12px; right: 12px; color: hsl(220 10% 70%);">
                    <x-heroicon-o-x-mark class="w-6 h-6" />
                </button>

                <div class="p-6">
                    <!-- Header -->
                    <div class="flex items-center gap-4 mb-6">
                        <div class="shrink-0 shadow-lg"
                            style="width: 56px; height: 56px; border-radius: 50%; background: hsl(220 70% 55%); color: white; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; font-weight: bold; border: 3px solid hsl(220 15% 85%);">
                            {{ strtoupper(substr($selectedPerson['name'], 0, 2)) }}
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold" style="color: white;">{{ $selectedPerson['name'] }}</h2>
                            <p style="color: hsl(220 10% 60%);">{{ $selectedPerson['role'] }}</p>
                        </div>
                    </div>

                    <!-- Info Grid -->
                    <div class="grid grid-cols-2 gap-4 mt-6 mb-6 text-sm">
                        <div class="flex items-center gap-2">
                            <x-heroicon-o-building-office class="w-4 h-4" style="color: hsl(220 10% 50%);" />
                            <div>
                                <span style="color: hsl(220 10% 50%);">Departamento:</span>
                                <span class="font-medium ml-1"
                                    style="color: hsl(220 15% 90%);">{{ $selectedPerson['department'] ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <x-heroicon-o-user class="w-4 h-4" style="color: hsl(220 10% 50%);" />
                            <div>
                                <span style="color: hsl(220 10% 50%);">Gestor:</span>
                                <span class="font-medium ml-1"
                                    style="color: hsl(220 15% 90%);">{{ $selectedPerson['manager_name'] ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <x-heroicon-o-calendar class="w-4 h-4" style="color: hsl(220 10% 50%);" />
                            <div>
                                <span style="color: hsl(220 10% 50%);">Admissão:</span>
                                <span class="font-medium ml-1"
                                    style="color: hsl(220 15% 90%);">{{ $selectedPerson['admitted_at'] ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <x-heroicon-o-clipboard-document-check class="w-4 h-4" style="color: hsl(220 10% 50%);" />
                            <div>
                                <span style="color: hsl(220 10% 50%);">Avaliação:</span>
                                <span class="font-medium ml-1"
                                    style="color: hsl(220 15% 90%);">{{ $selectedPerson['evaluation_date'] ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Matrix Position Box -->
                    <div class="rounded-xl mt-6 mb-6"
                        style="background: hsl(220 20% 16%); border: 1px solid hsl(220 20% 25%); padding: 1rem 1rem;">

                        <div class="flex items-center gap-1">
                            <x-heroicon-o-star class="w-5 h-5" style="color: hsl(220 70% 55%);" />
                            <h3 class="font-medium" style="color: hsl(220 15% 90%);">Posição na Matriz</h3>
                        </div>

                        <div class="flex items-center justify-between gap-2">
                            <div class="space-y-2 text-sm flex-1">
                                <div class="flex items-center gap-2">
                                    <x-heroicon-o-chart-bar class="w-4 h-4" style="color: hsl(142 70% 50%);" />
                                    <span style="color: hsl(220 10% 60%);">Desempenho:</span>
                                    <span class="font-semibold" style="color: hsl(220 15% 95%);">
                                        {{ match ($selectedPerson['position']['performance']) { 1 => 'Baixo', 2 => 'Médio', 3 => 'Alto', default => '-'} }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <x-heroicon-o-arrow-trending-up class="w-4 h-4" style="color: hsl(199 80% 55%);" />
                                    <span style="color: hsl(220 10% 60%);">Potencial:</span>
                                    <span class="font-semibold" style="color: hsl(220 15% 95%);">
                                        {{ match ($selectedPerson['position']['potential']) { 1 => 'Baixo', 2 => 'Médio', 3 => 'Alto', default => '-'} }}
                                    </span>
                                </div>
                            </div>

                            <!-- Big Matrix Visual -->
                            <div class="flex flex-col items-center shrink-0">
                                <span class="text-[10px] font-medium mb-1" style="color: hsl(220 10% 50%);">Potencial
                                    ↑</span>
                                <div
                                    style="display: inline-grid; grid-template-columns: repeat(3, 1fr); gap: 6px; padding: 8px; background: hsl(220 25% 12%); border: 1px solid hsl(220 20% 25%); border-radius: 8px;">
                                    @for ($pot = 3; $pot >= 1; $pot--)
                                        @for ($perf = 1; $perf <= 3; $perf++)
                                            @php
                                                $isActive = ($selectedPerson['position']['potential'] == $pot && $selectedPerson['position']['performance'] == $perf);
                                                $cellCatKey = getCategoryKey(['potential' => $pot, 'performance' => $perf]);
                                                $cellColor = $isActive ? $categoryConfig[$cellCatKey]['color'] : 'hsl(220 20% 25%)';
                                            @endphp
                                            <div
                                                style="width: 28px; height: 28px; border-radius: 4px; background: {{ $cellColor }}; {{ $isActive ? 'box-shadow: 0 0 12px ' . $cellColor . ';' : '' }}">
                                            </div>
                                        @endfor
                                    @endfor
                                </div>
                                <span class="text-[10px] font-medium mt-1" style="color: hsl(220 10% 50%);">Desempenho
                                    →</span>
                            </div>
                        </div>
                    </div>

                    <!-- Classification -->
                    <div class="mt-6 mb-4">
                        <div class="flex items-center gap-3">
                            <span class="text-sm" style="color: hsl(220 10% 60%);">Classificação:</span>
                            <span class="inline-flex items-center font-semibold rounded-full px-3 py-1 text-sm"
                                style="background: {{ $modalCat['bgColor'] }}; color: {{ $modalCat['color'] }};">
                                {{ $modalCat['label'] }}
                            </span>
                        </div>
                        <p class="text-sm italic mt-2" style="color: hsl(220 10% 60%);">
                            "{{ $modalCat['description'] }}"
                        </p>
                    </div>

                    <!-- Recommendation -->
                    <div class="rounded-xl p-4"
                        style="background: hsl(142 70% 50% / 0.1); border: 1px solid hsl(142 70% 50% / 0.3);">
                        <h4 class="text-xs uppercase font-bold tracking-wider mb-1" style="color: hsl(142 70% 50%);">
                            Recomendação</h4>
                        <p class="text-sm leading-relaxed" style="color: hsl(142 70% 85%);">
                            {{ $modalCat['recommendation'] }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <style>
        @keyframes scale-in {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .animate-scale-in {
            animation: scale-in 0.2s ease-out forwards;
        }
    </style>
</x-filament-panels::page>