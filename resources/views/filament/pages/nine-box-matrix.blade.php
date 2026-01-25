<x-filament-panels::page>
    @php
        // Note: Theme is handled via CSS custom properties defined in the style block below
        // This allows proper detection of Filament's dark/light mode which is client-side

        // 9-Box Category mappings with CSS variable references for theme support
        // Colors are defined in the style block with :root and .dark selectors
        $categoryConfig = [
            'star' => [
                'label' => 'Estrela',
                'color' => 'var(--nb-star)',
                'bgColor' => 'var(--nb-star-bg)',
                'description' => 'Alto potencial combinado com excelente desempenho',
                'recommendation' => 'Preparar para posições de liderança e projetos estratégicos'
            ],
            'high-performer' => [
                'label' => 'Alto Desempenho',
                'color' => 'var(--nb-high-performer)',
                'bgColor' => 'var(--nb-high-performer-bg)',
                'description' => 'Entrega consistente com potencial moderado de crescimento',
                'recommendation' => 'Manter engajado com desafios e reconhecimento'
            ],
            'high-potential' => [
                'label' => 'Alto Potencial',
                'color' => 'var(--nb-high-potential)',
                'bgColor' => 'var(--nb-high-potential-bg)',
                'description' => 'Grande potencial de crescimento com desempenho em desenvolvimento',
                'recommendation' => 'Investir em desenvolvimento e mentoria'
            ],
            'core-player' => [
                'label' => 'Mantenedor',
                'color' => 'var(--nb-core-player)',
                'bgColor' => 'var(--nb-core-player-bg)',
                'description' => 'Desempenho e potencial equilibrados',
                'recommendation' => 'Desenvolver habilidades específicas para crescimento'
            ],
            'solid-performer' => [
                'label' => 'Profissional Eficaz',
                'color' => 'var(--nb-solid-performer)',
                'bgColor' => 'var(--nb-solid-performer-bg)',
                'description' => 'Desempenho sólido com foco na função atual',
                'recommendation' => 'Valorizar expertise e considerar como referência técnica'
            ],
            'inconsistent' => [
                'label' => 'Inconsistente',
                'color' => 'var(--nb-inconsistent)',
                'bgColor' => 'var(--nb-inconsistent-bg)',
                'description' => 'Alto potencial mas desempenho abaixo do esperado',
                'recommendation' => 'Investigar barreiras e oferecer suporte direcionado'
            ],
            'risk' => [
                'label' => 'Questionável',
                'color' => 'var(--nb-risk)',
                'bgColor' => 'var(--nb-risk-bg)',
                'description' => 'Desempenho moderado com potencial limitado',
                'recommendation' => 'Avaliar adequação à função e plano de melhoria'
            ],
            'underperformer' => [
                'label' => 'Insuficiente',
                'color' => 'var(--nb-underperformer)',
                'bgColor' => 'var(--nb-underperformer-bg)',
                'description' => 'Desempenho e potencial abaixo do esperado',
                'recommendation' => 'Plano de melhoria urgente ou realocação'
            ],
            'enigma' => [
                'label' => 'Enigma',
                'color' => 'var(--nb-enigma)',
                'bgColor' => 'var(--nb-enigma-bg)',
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
        style="background: var(--nb-card-bg); border: 1px solid var(--nb-card-border); padding: 1rem; border-radius: 0.75rem;">
        <div class="w-full md:w-1/3">
            <div class="relative">
                <x-heroicon-o-magnifying-glass class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
                <input type="text" wire:model.live.debounce.500ms="search" placeholder="Buscar colaborador..."
                    class="pl-10 w-full rounded-lg text-sm"
                    style="background: var(--nb-input-bg); border: 1px solid var(--nb-input-border); color: var(--nb-text-primary); padding: 0.5rem 0.75rem 0.5rem 2.5rem;" />
            </div>
        </div>

        <div class="flex gap-4 w-full md:w-auto">
            <select wire:model.live="cycleId" class="rounded-lg text-sm py-2 pl-3 pr-10"
                style="background: var(--nb-input-bg); border: 1px solid var(--nb-input-border); color: var(--nb-text-primary);">
                <option value="">Selecione o Ciclo...</option>
                @foreach($cycles as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>

            <button wire:click="toggleFilters"
                class="px-4 py-2 text-sm font-medium rounded-lg transition-colors hover:opacity-80"
                style="background: {{ $showFilters ? 'var(--nb-avatar-bg)' : 'var(--nb-input-bg)' }}; border: 1px solid var(--nb-input-border); color: {{ $showFilters ? 'var(--nb-avatar-text)' : 'var(--nb-text-primary)' }};">
                <div class="flex items-center gap-2">
                    <x-heroicon-o-funnel class="w-4 h-4" />
                    Filtros
                </div>
            </button>
        </div>
    </div>

    <!-- Filtros Expansíveis -->
    <div x-show="$wire.showFilters" x-transition.origin.top.duration.200ms
        class="mb-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 p-4 rounded-xl shadow-inner"
        style="background: var(--nb-matrix-bg); border: 1px solid var(--nb-card-border);">

        <div>
            <label class="block text-xs font-medium mb-1 pl-1"
                style="color: var(--nb-text-secondary);">Departamento</label>
            <select wire:model.live="selectedDepartment" class="w-full rounded-lg text-sm"
                style="background: var(--nb-input-bg); border: 1px solid var(--nb-input-border); color: var(--nb-text-primary);">
                <option value="">Todos os Departamentos</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept }}">{{ $dept }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-xs font-medium mb-1 pl-1"
                style="color: var(--nb-text-secondary);">Quadrante</label>
            <select wire:model.live="selectedQuadrant" class="w-full rounded-lg text-sm"
                style="background: var(--nb-input-bg); border: 1px solid var(--nb-input-border); color: var(--nb-text-primary);">
                <option value="">Todos os Quadrantes</option>
                @foreach($quadrants as $key => $q)
                    <option value="{{ $key }}">{{ $q['label'] }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex items-end">
            <button wire:click="$set('selectedDepartment', null); $set('selectedQuadrant', null);"
                class="text-xs hover:underline mb-2" style="color: var(--nb-text-muted);">
                Limpar Filtros
            </button>
        </div>
    </div>

    @if($cycleId)
        <div class="mb-4 text-sm font-medium flex items-center gap-2" style="color: var(--nb-text-secondary);">
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
                    style="background: var(--nb-card-bg); border: 1px solid var(--nb-card-border); border-radius: 0.75rem; padding: 1.25rem;">

                    <div class="flex items-center gap-4">
                        <!-- Avatar -->
                        <div class="rounded-full flex items-center justify-center font-semibold shrink-0 shadow-md"
                            style="width: 48px; height: 48px; font-size: 0.9rem; background: var(--nb-avatar-bg); color: var(--nb-avatar-text); border: 2px solid var(--nb-avatar-border);">
                            {{ strtoupper(substr($employee['name'], 0, 2)) }}
                        </div>

                        <!-- Info -->
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold truncate transition-colors group-hover:text-blue-400"
                                style="color: var(--nb-text-primary);">
                                {{ $employee['name'] }}
                            </h3>
                            <p class="text-sm truncate" style="color: var(--nb-text-secondary);">{{ $employee['role'] }}
                            </p>
                            @if($employee['department'])
                                <p class="text-xs mt-0.5 truncate" style="color: var(--nb-text-muted);">
                                    {{ $employee['department'] }}
                                </p>
                            @endif
                        </div>

                        <!-- Status Dot + Mini Matrix -->
                        <div class="shrink-0" style="display: flex; flex-direction: row; align-items: center; gap: 0.5rem;">
                            <!-- Mini Matrix -->
                            <div
                                style="display: inline-grid; grid-template-columns: repeat(3, 1fr); gap: 2px; padding: 4px; background: var(--nb-matrix-bg); border-radius: 4px;">
                                @for ($pot = 3; $pot >= 1; $pot--)
                                    @for ($perf = 1; $perf <= 3; $perf++)
                                        @php
                                            $isActive = ($employee['position']['potential'] == $pot && $employee['position']['performance'] == $perf);
                                            $cellCatKey = getCategoryKey(['potential' => $pot, 'performance' => $perf]);
                                            $cellColor = $isActive ? $categoryConfig[$cellCatKey]['color'] : 'var(--nb-matrix-inactive)';
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
                        style="border-top: 1px solid var(--nb-divider);">
                        <span class="inline-flex items-center font-semibold rounded-full text-xs"
                            style="background: {{ $cat['bgColor'] }}; color: {{ $cat['color'] }}; padding: 6px 12px;">
                            {{ $cat['label'] }}
                        </span>
                        <span class="text-xs text-right line-clamp-1" style="color: var(--nb-text-secondary);">
                            {{ Str::limit($cat['description'], 35) }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="flex flex-col items-center justify-center py-20 rounded-xl"
            style="background: var(--nb-card-bg); border: 1px dashed var(--nb-card-border);">
            <x-heroicon-o-chart-bar class="w-16 h-16 mb-4" style="color: var(--nb-text-muted);" />
            <h3 class="text-lg font-medium" style="color: var(--nb-text-primary);">Nenhum ciclo selecionado</h3>
            <p class="text-sm mt-1" style="color: var(--nb-text-secondary);">Selecione um ciclo para visualizar a
                matriz</p>
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
            <div class="absolute inset-0 backdrop-blur-sm transition-opacity" style="background: var(--nb-overlay);"
                wire:click="closeModal"></div>

            <!-- Modal Container -->
            <div class="relative w-full max-w-lg overflow-hidden transform transition-all animate-scale-in"
                style="background: var(--nb-card-bg); border: 1px solid var(--nb-card-border); border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);">

                <!-- Close Button -->
                <button wire:click="closeModal" class="transition-colors z-10 p-1 rounded-full"
                    style="position: absolute; top: 12px; right: 12px; color: var(--nb-text-muted);">
                    <x-heroicon-o-x-mark class="w-6 h-6" />
                </button>

                <div class="p-6">
                    <!-- Header -->
                    <div class="flex items-center gap-4 mb-6">
                        <div class="shrink-0 shadow-lg"
                            style="width: 56px; height: 56px; border-radius: 50%; background: var(--nb-avatar-bg); color: var(--nb-avatar-text); display: flex; align-items: center; justify-content: center; font-size: 1.25rem; font-weight: bold; border: 3px solid var(--nb-avatar-border);">
                            {{ strtoupper(substr($selectedPerson['name'], 0, 2)) }}
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold" style="color: var(--nb-text-primary);">
                                {{ $selectedPerson['name'] }}
                            </h2>
                            <p style="color: var(--nb-text-secondary);">{{ $selectedPerson['role'] }}</p>
                        </div>
                    </div>

                    <!-- Info Grid -->
                    <div class="grid grid-cols-2 gap-4 mt-6 mb-6 text-sm">
                        <div class="flex items-center gap-2">
                            <x-heroicon-o-building-office class="w-4 h-4" style="color: var(--nb-text-muted);" />
                            <div>
                                <span style="color: var(--nb-text-muted);">Departamento:</span>
                                <span class="font-medium ml-1"
                                    style="color: var(--nb-text-primary);">{{ $selectedPerson['department'] ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <x-heroicon-o-user class="w-4 h-4" style="color: var(--nb-text-muted);" />
                            <div>
                                <span style="color: var(--nb-text-muted);">Gestor:</span>
                                <span class="font-medium ml-1"
                                    style="color: var(--nb-text-primary);">{{ $selectedPerson['manager_name'] ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <x-heroicon-o-calendar class="w-4 h-4" style="color: var(--nb-text-muted);" />
                            <div>
                                <span style="color: var(--nb-text-muted);">Admissão:</span>
                                <span class="font-medium ml-1"
                                    style="color: var(--nb-text-primary);">{{ $selectedPerson['admitted_at'] ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <x-heroicon-o-clipboard-document-check class="w-4 h-4" style="color: var(--nb-text-muted);" />
                            <div>
                                <span style="color: var(--nb-text-muted);">Avaliação:</span>
                                <span class="font-medium ml-1"
                                    style="color: var(--nb-text-primary);">{{ $selectedPerson['evaluation_date'] ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Matrix Position Box -->
                    <div class="rounded-xl mt-6 mb-6"
                        style="background: var(--nb-matrix-bg); border: 1px solid var(--nb-card-border); padding: 1rem 1rem;">

                        <div class="flex items-center gap-1">
                            <x-heroicon-o-star class="w-5 h-5" style="color: hsl(220 70% 55%);" />
                            <h3 class="font-medium" style="color: var(--nb-text-primary);">Posição na Matriz</h3>
                        </div>

                        <div class="flex items-center justify-between gap-2">
                            <div class="space-y-2 text-sm flex-1">
                                <div class="flex items-center gap-2">
                                    <x-heroicon-o-chart-bar class="w-4 h-4" style="color: hsl(142 70% 50%);" />
                                    <span style="color: var(--nb-text-secondary);">Desempenho:</span>
                                    <span class="font-semibold" style="color: var(--nb-text-primary);">
                                        {{ match ($selectedPerson['position']['performance']) { 1 => 'Baixo', 2 => 'Médio', 3 => 'Alto', default => '-'} }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <x-heroicon-o-arrow-trending-up class="w-4 h-4" style="color: hsl(199 80% 55%);" />
                                    <span style="color: var(--nb-text-secondary);">Potencial:</span>
                                    <span class="font-semibold" style="color: var(--nb-text-primary);">
                                        {{ match ($selectedPerson['position']['potential']) { 1 => 'Baixo', 2 => 'Médio', 3 => 'Alto', default => '-'} }}
                                    </span>
                                </div>
                            </div>

                            <!-- Big Matrix Visual -->
                            <div class="flex flex-col items-center shrink-0">
                                <span class="text-[10px] font-medium mb-1" style="color: var(--nb-text-muted);">Potencial
                                    ↑</span>
                                <div
                                    style="display: inline-grid; grid-template-columns: repeat(3, 1fr); gap: 6px; padding: 8px; background: var(--nb-matrix-bg); border: 1px solid var(--nb-card-border); border-radius: 8px;">
                                    @for ($pot = 3; $pot >= 1; $pot--)
                                        @for ($perf = 1; $perf <= 3; $perf++)
                                            @php
                                                $isActive = ($selectedPerson['position']['potential'] == $pot && $selectedPerson['position']['performance'] == $perf);
                                                $cellCatKey = getCategoryKey(['potential' => $pot, 'performance' => $perf]);
                                                $cellColor = $isActive ? $categoryConfig[$cellCatKey]['color'] : 'var(--nb-matrix-inactive)';
                                            @endphp
                                            <div
                                                style="width: 28px; height: 28px; border-radius: 4px; background: {{ $cellColor }}; {{ $isActive ? 'box-shadow: 0 0 12px ' . $cellColor . ';' : '' }}">
                                            </div>
                                        @endfor
                                    @endfor
                                </div>
                                <span class="text-[10px] font-medium mt-1" style="color: var(--nb-text-muted);">Desempenho
                                    →</span>
                            </div>
                        </div>
                    </div>

                    <!-- Classification -->
                    <div class="mt-6 mb-4">
                        <div class="flex items-center gap-3">
                            <span class="text-sm" style="color: var(--nb-text-secondary);">Classificação:</span>
                            <span class="inline-flex items-center font-semibold rounded-full px-3 py-1 text-sm"
                                style="background: {{ $modalCat['bgColor'] }}; color: {{ $modalCat['color'] }};">
                                {{ $modalCat['label'] }}
                            </span>
                        </div>
                        <p class="text-sm italic mt-2" style="color: var(--nb-text-secondary);">
                            "{{ $modalCat['description'] }}"
                        </p>
                    </div>

                    <!-- Recommendation -->
                    <div class="rounded-xl p-4"
                        style="background: var(--nb-rec-bg); border: 1px solid var(--nb-rec-border);">
                        <h4 class="text-xs uppercase font-bold tracking-wider mb-1" style="color: hsl(142 70% 50%);">
                            Recomendação</h4>
                        <p class="text-sm leading-relaxed" style="color: var(--nb-rec-text);">
                            {{ $modalCat['recommendation'] }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <style>
        /* Light theme (default) */
        :root {
            --nb-card-bg: hsl(0 0% 100%);
            --nb-card-border: hsl(220 15% 88%);
            --nb-input-bg: hsl(0 0% 100%);
            --nb-input-border: hsl(220 15% 88%);
            --nb-text-primary: hsl(220 20% 10%);
            --nb-text-secondary: hsl(220 10% 45%);
            --nb-text-muted: hsl(220 10% 55%);
            --nb-avatar-bg: hsl(220 70% 55%);
            --nb-avatar-text: white;
            --nb-avatar-border: hsl(220 15% 80%);
            --nb-matrix-inactive: hsl(220 15% 90%);
            --nb-matrix-bg: hsl(220 15% 94%);
            --nb-divider: hsl(220 15% 90%);
            --nb-overlay: rgba(0, 0, 0, 0.5);
            --nb-rec-text: hsl(142 50% 30%);
            --nb-rec-bg: hsl(142 70% 50% / 0.1);
            --nb-rec-border: hsl(142 70% 50% / 0.3);

            /* Category colors - Light theme */
            --nb-star: hsl(142 76% 36%);
            --nb-star-bg: hsl(142 76% 95%);
            --nb-high-performer: hsl(142 60% 45%);
            --nb-high-performer-bg: hsl(142 60% 95%);
            --nb-high-potential: hsl(199 89% 48%);
            --nb-high-potential-bg: hsl(199 89% 95%);
            --nb-core-player: hsl(262 83% 58%);
            --nb-core-player-bg: hsl(262 83% 95%);
            --nb-solid-performer: hsl(220 70% 50%);
            --nb-solid-performer-bg: hsl(220 70% 95%);
            --nb-inconsistent: hsl(45 93% 47%);
            --nb-inconsistent-bg: hsl(45 93% 95%);
            --nb-risk: hsl(25 95% 53%);
            --nb-risk-bg: hsl(25 95% 95%);
            --nb-underperformer: hsl(0 84% 50%);
            --nb-underperformer-bg: hsl(0 84% 95%);
            --nb-enigma: hsl(280 70% 50%);
            --nb-enigma-bg: hsl(280 70% 95%);
        }

        /* Dark theme */
        .dark {
            --nb-card-bg: hsl(220 25% 12%);
            --nb-card-border: hsl(220 20% 20%);
            --nb-input-bg: hsl(220 20% 16%);
            --nb-input-border: hsl(220 20% 20%);
            --nb-text-primary: hsl(220 15% 95%);
            --nb-text-secondary: hsl(220 10% 60%);
            --nb-text-muted: hsl(220 10% 45%);
            --nb-avatar-bg: hsl(220 70% 55%);
            --nb-avatar-text: white;
            --nb-avatar-border: hsl(220 15% 85%);
            --nb-matrix-inactive: hsl(220 20% 25%);
            --nb-matrix-bg: hsl(220 20% 16%);
            --nb-divider: hsl(220 20% 20%);
            --nb-overlay: rgba(0, 0, 0, 0.8);
            --nb-rec-text: hsl(142 70% 85%);
            --nb-rec-bg: hsl(142 70% 50% / 0.1);
            --nb-rec-border: hsl(142 70% 50% / 0.3);

            /* Category colors - Dark theme */
            --nb-star: hsl(142 70% 50%);
            --nb-star-bg: hsl(142 70% 15%);
            --nb-high-performer: hsl(142 55% 55%);
            --nb-high-performer-bg: hsl(142 55% 15%);
            --nb-high-potential: hsl(199 80% 55%);
            --nb-high-potential-bg: hsl(199 80% 15%);
            --nb-core-player: hsl(262 75% 65%);
            --nb-core-player-bg: hsl(262 75% 15%);
            --nb-solid-performer: hsl(220 65% 60%);
            --nb-solid-performer-bg: hsl(220 65% 15%);
            --nb-inconsistent: hsl(45 85% 55%);
            --nb-inconsistent-bg: hsl(45 85% 15%);
            --nb-risk: hsl(25 90% 58%);
            --nb-risk-bg: hsl(25 90% 15%);
            --nb-underperformer: hsl(0 75% 55%);
            --nb-underperformer-bg: hsl(0 75% 15%);
            --nb-enigma: hsl(280 65% 60%);
            --nb-enigma-bg: hsl(280 65% 15%);
        }

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