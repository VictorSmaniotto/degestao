<x-filament-panels::page>
    <div class="space-y-6">

        <!-- INTRODUÇÃO -->
        <x-filament::section collapsible>
            <x-slot name="heading">
                O que é o DeGestão?
            </x-slot>

            <div class="prose dark:prose-invert max-w-none text-sm">
                <p>
                    O <strong>DeGestão</strong> é uma plataforma de gestão de desempenho baseada em evidências,
                    projetada para eliminar a subjetividade nas avaliações de colaboradores.
                </p>
                <p>
                    Diferente de sistemas de RH tradicionais onde o gestor "acha" que o funcionário é bom, aqui
                    utilizamos um modelo matemático para calcular a posição do colaborador na <strong>Matriz
                        9-Box</strong> com base em fatos observados (evidências).
                </p>

                <h3 class="font-bold mt-4">Pilares da Metodologia</h3>
                <ul class="list-disc pl-5 mt-2 space-y-1">
                    <li><strong>Evidência Sob Opinião:</strong> Só vale o que foi registrado como fato observável.</li>
                    <li><strong>Separação Performance x Potencial:</strong> Performance é o que foi entregue (passado).
                        Potencial é a capacidade de crescer (futuro).</li>
                    <li><strong>Contexto Importa:</strong> Uma entrega em um ambiente caótico vale mais do que em um
                        ambiente estruturado.</li>
                </ul>
            </div>
        </x-filament::section>

        <!-- GUIA RÁPIDO -->
        <x-filament::section collapsible>
            <x-slot name="heading">
                Como usar o sistema? (Passo a Passo)
            </x-slot>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">

                <!-- Passo 1 -->
                <div class="p-4 rounded-lg bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10">
                    <div class="flex items-center gap-2 mb-2 text-primary-600 dark:text-primary-400">
                        <x-heroicon-o-users class="w-5 h-5" />
                        <h3 class="font-bold">1. Cadastrar Pessoas</h3>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400">
                        Antes de tudo, cadastre seus colaboradores. Preencha nome, cargo, departamento e data de
                        admissão.
                    </p>
                </div>

                <!-- Passo 2 -->
                <div class="p-4 rounded-lg bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10">
                    <div class="flex items-center gap-2 mb-2 text-primary-600 dark:text-primary-400">
                        <x-heroicon-o-clock class="w-5 h-5" />
                        <h3 class="font-bold">2. Definir Ciclo</h3>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400">
                        Crie um Ciclo de Avaliação (ex: "Q1 2024"). Defina as datas de início e fim. As evidências só
                        contam se estiverem dentro da data do ciclo ativo.
                    </p>
                </div>

                <!-- Passo 3 -->
                <div class="p-4 rounded-lg bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10">
                    <div class="flex items-center gap-2 mb-2 text-primary-600 dark:text-primary-400">
                        <x-heroicon-o-map class="w-5 h-5" />
                        <h3 class="font-bold">3. Configurar Contextos</h3>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400">
                        Crie contextos onde as coisas acontecem (ex: "Projeto Crítico", "Dia a Dia"). Defina a
                        complexidade.
                    </p>
                </div>

                <!-- Passo 4 -->
                <div class="p-4 rounded-lg bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10">
                    <div class="flex items-center gap-2 mb-2 text-primary-600 dark:text-primary-400">
                        <x-heroicon-o-clipboard-document-check class="w-5 h-5" />
                        <h3 class="font-bold">4. Registrar Evidências</h3>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400">
                        Aqui acontece a mágica. Durante o ciclo, registre fatos.
                        <br><strong>Exemplo:</strong> "Entregou relatório X antes do prazo".
                        <br>Classifique como <em>Performance</em> ou <em>Potencial</em> e dê a
                        <strong>intensidade</strong> (0 a 4).
                    </p>
                </div>

            </div>
        </x-filament::section>

        <!-- MATRIZ 9-BOX EXPLICADA -->
        <x-filament::section collapsible>
            <x-slot name="heading">
                Entendendo a Matriz 9-Box
            </x-slot>

            <div class="space-y-4">
                <p class="text-sm">
                    A matriz cruza dois eixos: <strong>Desempenho</strong> (Eixo X) e <strong>Potencial</strong> (Eixo
                    Y).
                </p>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    <!-- Coluna Baixo Desempenho -->
                    <div class="space-y-4">
                        <div class="p-3 rounded-lg border-l-4 border-l-yellow-400 bg-gray-50 dark:bg-white/5">
                            <h4 class="font-bold text-xs uppercase mb-1">Enigma (Alto Potencial, Baixo Desempenho)</h4>
                            <p class="text-xs text-gray-500">Pessoa muito capaz mas que não está entregando. Problema de
                                motivação ou alocação errada?</p>
                        </div>
                        <div class="p-3 rounded-lg border-l-4 border-l-orange-400 bg-gray-50 dark:bg-white/5">
                            <h4 class="font-bold text-xs uppercase mb-1">Questionável (Médio Potencial, Baixo
                                Desempenho)</h4>
                            <p class="text-xs text-gray-500">Precisa de feedback claro. Se não melhorar, vira risco.</p>
                        </div>
                        <div class="p-3 rounded-lg border-l-4 border-l-red-500 bg-gray-50 dark:bg-white/5">
                            <h4 class="font-bold text-xs uppercase mb-1">Insuficiente (Baixo Potencial, Baixo
                                Desempenho)</h4>
                            <p class="text-xs text-gray-500">Risco crítico. Necessário plano de recuperação imediato ou
                                desligamento.</p>
                        </div>
                    </div>

                    <!-- Coluna Médio Desempenho -->
                    <div class="space-y-4">
                        <div class="p-3 rounded-lg border-l-4 border-l-green-500 bg-gray-50 dark:bg-white/5">
                            <h4 class="font-bold text-xs uppercase mb-1">Forte Desempenho (Alto Potencial, Médio
                                Desempenho)</h4>
                            <p class="text-xs text-gray-500">Está crescendo rápido. Dê desafios maiores.</p>
                        </div>
                        <div class="p-3 rounded-lg border-l-4 border-l-purple-500 bg-gray-50 dark:bg-white/5">
                            <h4 class="font-bold text-xs uppercase mb-1">Mantenedor (Médio Potencial, Médio Desempenho)
                            </h4>
                            <p class="text-xs text-gray-500">O coração da empresa. Entrega o que se espera e mantém a
                                estabilidade.</p>
                        </div>
                        <div class="p-3 rounded-lg border-l-4 border-l-pink-400 bg-gray-50 dark:bg-white/5">
                            <h4 class="font-bold text-xs uppercase mb-1">Eficaz (Baixo Potencial, Médio Desempenho)</h4>
                            <p class="text-xs text-gray-500">Faz o trabalho bem, mas talvez tenha atingido seu teto de
                                complexidade atual.</p>
                        </div>
                    </div>

                    <!-- Coluna Alto Desempenho -->
                    <div class="space-y-4">
                        <div class="p-3 rounded-lg border-l-4 border-l-green-600 bg-gray-50 dark:bg-white/5">
                            <h4 class="font-bold text-xs uppercase mb-1">Estrela (Alto Potencial, Alto Desempenho)</h4>
                            <p class="text-xs text-gray-500">Top performer. Futuro líder. Precisa de retenção agressiva.
                            </p>
                        </div>
                        <div class="p-3 rounded-lg border-l-4 border-l-blue-600 bg-gray-50 dark:bg-white/5">
                            <h4 class="font-bold text-xs uppercase mb-1">Alto Potencial (Médio Potencial, Alto
                                Desempenho)</h4>
                            <p class="text-xs text-gray-500">Performance excelente. Pode assumir liderança técnica ou
                                novos projetos em breve.</p>
                        </div>
                        <div class="p-3 rounded-lg border-l-4 border-l-indigo-400 bg-gray-50 dark:bg-white/5">
                            <h4 class="font-bold text-xs uppercase mb-1">Comprometido (Baixo Potencial, Alto Desempenho)
                            </h4>
                            <p class="text-xs text-gray-500">Especialista. Entrega muito resultado na função atual. Não
                                tente promover para gestão sem preparo.</p>
                        </div>
                    </div>
                </div>
            </div>
        </x-filament::section>

        <!-- DICAS -->
        <x-filament::section collapsible collapsed>
            <x-slot name="heading">
                FAQ e Dicas
            </x-slot>
            <div class="space-y-2 text-sm">
                <p><strong>Meu gráfico Radar está uma linha reta!</strong><br>Isso acontece quando você tem evidências
                    em apenas 1 ou 2 dimensões. Para formar a "teia", avalie o colaborador em pelo menos 3 eixos (ex:
                    Técnica, Comportamental e Entrega).</p>
                <hr class="border-gray-200 dark:border-white/10">
                <p><strong>A nota não muda mesmo cadastrando evidência.</strong><br>Verifique se a data da evidência
                    está dentro do <em>Ciclo Ativo</em> selecionado na página da Matriz.</p>
            </div>
        </x-filament::section>

    </div>
</x-filament-panels::page>