<x-filament-panels::page>
    <form wire:submit="save" class="mb-12">
        {{ $this->form }}

        <div class="mt-12 flex justify-end"> <!-- Aumentei margin para mt-8 -->
            <x-filament::button type=" submit" size="lg" style="margin-top: 1rem;">
                Salvar Alterações
            </x-filament::button>
        </div>
    </form>

    <x-filament::section class="mt-8" collapsible>
        <x-slot name="heading">
            Meu Radar de Competências (Ciclo Atual)
        </x-slot>

        @php
            $chartData = $this->getRadarData();
            $hasData = !empty($chartData['labels']) && !empty($chartData['datasets']);
        @endphp

        @if($hasData)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center" x-data="radarChart(@js($chartData))" x-ignore
                ax-load>
                <div>
                    <p class="text-sm text-gray-400 mb-4">
                        Este gráfico visualiza seu desenvolvimento nas principais competências avaliadas pela organização.
                        Use-o para identificar seus pontos fortes e áreas de melhoria contínua.
                    </p>

                    <div class="space-y-2 text-sm text-gray-300">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-sky-400 rounded-full"></div>
                            <span>Área preenchida: Seu nível atual</span>
                        </div>
                    </div>
                </div>

                <!-- Container com altura fixa para evitar resize loop -->
                <div class="h-72 relative w-full flex items-center justify-center">
                    <canvas x-ref="canvas"></canvas>
                </div>
            </div>
        @else
            <div class="text-center py-8 text-gray-400">
                <x-heroicon-o-chart-bar class="w-12 h-12 mx-auto mb-3 opacity-50" />
                <p>Nenhum dado de avaliação disponível para visualização no momento.</p>
            </div>
        @endif
    </x-filament::section>

    <!-- Chart.js via CDN (se nao tiver no bundle) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('radarChart', (chartData) => ({
                chart: null,
                init() {
                    if (!chartData) return;

                    // Pequeno delay para garantir que o DOM (canvas) esteja pronto e visível
                    setTimeout(() => {
                        this.renderChart(chartData);
                    }, 100);
                },
                renderChart(data) {
                    const canvas = this.$refs.canvas;
                    if (!canvas) return;

                    const ctx = canvas.getContext('2d');

                    this.chart = new Chart(ctx, {
                        type: 'radar',
                        data: data,
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                r: {
                                    angleLines: { display: true, color: 'rgba(255, 255, 255, 0.1)' },
                                    grid: { color: 'rgba(255, 255, 255, 0.1)' },
                                    pointLabels: { color: '#9ca3af', font: { size: 12 } },
                                    ticks: { backdropColor: 'transparent', color: '#9ca3af', stepSize: 20, min: 0, max: 100 },
                                    suggestedMin: 0,
                                    suggestedMax: 100
                                }
                            },
                            plugins: {
                                legend: { display: false }
                            }
                        }
                    });
                },
                destroy() {
                    if (this.chart) {
                        this.chart.destroy();
                    }
                }
            }))
        });
    </script>
</x-filament-panels::page>