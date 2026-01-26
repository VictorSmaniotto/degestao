<?php

namespace App\Filament\Pages;

use App\Models\Person;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class MyProfile extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    protected static ?string $navigationLabel = 'Meu Perfil';
    protected static ?string $title = 'Meu Perfil';
    protected static ?string $slug = 'meu-perfil';
    protected static ?int $navigationSort = 1; // Topo para todos

    protected static string $view = 'filament.pages.my-profile';

    public ?array $data = [];
    public ?Person $person = null;

    public function mount(): void
    {
        $user = Auth::user();

        // Se não tiver Person vinculado, tenta vincular ou cria dummy (em dev)
        // Em prod, mostraria erro.
        if (!$user)
            return;

        $this->person = $user->person;

        // Se user tem person, preenche dados.
        if ($this->person) {
            $this->form->fill([
                'name' => $this->person->name,
                'role' => $this->person->role,
                'department' => $this->person->department,
                'email' => $this->person->email,
                'phone' => $this->person->phone,
                'bio' => $this->person->bio,
                'avatar_path' => $this->person->avatar_path,
            ]);
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(['default' => 1, 'md' => 3]) // Responsivo: 1 col mobile, 3 cols desktop
                    ->schema([
                        // Coluna da Esquerda (Avatar e Infos Readonly) - Ocupa 1/3
                        Section::make()
                            ->columnSpan(['default' => 1, 'md' => 1])
                            ->schema([
                                FileUpload::make('avatar_path')
                                    ->label('Foto de Perfil')
                                    ->avatar()
                                    ->disk('public') // Força disco público
                                    ->image()
                                    ->imageEditor()
                                    ->directory('avatars')
                                    ->visibility('public')
                                    ->maxSize(10240) // 10MB
                                    ->imageResizeMode('cover')
                                    ->imageCropAspectRatio('1:1')
                                    ->imageResizeTargetWidth('500')
                                    ->imageResizeTargetHeight('500')
                                    ->columnSpanFull()
                                    ->alignCenter(),

                                \Filament\Forms\Components\Placeholder::make('name_display')
                                    ->label('Nome Completo')
                                    ->content(fn() => $this->person?->name ?? Auth::user()->name)
                                    ->extraAttributes(['class' => 'text-lg font-bold']),

                                \Filament\Forms\Components\Placeholder::make('role_display')
                                    ->label('Cargo')
                                    ->content(fn() => $this->person?->role ?? '-'),

                                \Filament\Forms\Components\Placeholder::make('department_display')
                                    ->label('Departamento')
                                    ->content(fn() => $this->person?->department ?? '-'),
                            ]),

                        // Coluna da Direita (Edição) - Ocupa 2/3
                        Section::make('Editar Informações')
                            ->description('Mantenha seus dados de contato e biografia atualizados.')
                            ->columnSpan(['default' => 1, 'md' => 2])
                            ->schema([
                                TextInput::make('email')
                                    ->label('E-mail Corporativo')
                                    ->disabled()
                                    ->helperText('Para alterar o e-mail, contate o RH.'),

                                TextInput::make('phone')
                                    ->label('Telefone / WhatsApp')
                                    ->tel()
                                    ->placeholder('(11) 99999-9999'),

                                Textarea::make('bio')
                                    ->label('Minha Biografia')
                                    ->rows(5)
                                    ->placeholder('Conte um pouco sobre sua trajetória, interesses e objetivos profissionais...')
                                    ->maxLength(500),
                            ]),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        // Se usuário não tem Person vinculado (ex: Admin puro), cria um Person on-the-fly ou avisa
        if (!$this->person) {
            Notification::make()
                ->title('Erro ao salvar')
                ->body('Seu usuário não possui perfil de colaborador vinculado.')
                ->danger()
                ->send();
            return;
        }

        $data = $this->form->getState();

        // Atualiza apenas campos permitidos
        $this->person->update([
            'phone' => $data['phone'],
            'bio' => $data['bio'],
            'avatar_path' => $data['avatar_path'],
        ]);

        Notification::make()
            ->title('Perfil atualizado com sucesso!')
            ->success()
            ->send();
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Salvar Alterações')
                ->submit('save'),
        ];
    }

    /**
     * Obter dados para o Radar Chart (Dashboard Pessoal)
     */
    public function getRadarData(): array
    {
        if (!$this->person)
            return [];

        // Tenta buscar ciclo ativo
        $cycle = \App\Models\Cycle::active()->latest('start_date')->first();

        // Se não tiver ativo, pega o último encerrado (para mostrar histórico)
        if (!$cycle) {
            $cycle = \App\Models\Cycle::latest('end_date')->first();
        }

        // Se não houver ciclo ou cálculo, retorna array vazio para o Blade tratar
        if (!$cycle)
            return [];

        $aggregator = app(\App\Domains\Aggregation\Services\EvidenceAggregator::class);
        $result = $aggregator->calculate($this->person, $cycle);

        // Se não tiver counts, pode ser que array de datasets fique zerado, ok.

        // Retornar estrutura pronta para Chart.js
        return [
            'labels' => ['Comportamental', 'Técnico', 'Entrega', 'Cultura', 'Liderança', 'Inovação'],
            'datasets' => [
                [
                    'label' => 'Meus Resultados',
                    'data' => [$result->getAverage(), $result->x, $result->y, 75, 60, 80],
                    'fill' => true,
                    'backgroundColor' => 'rgba(56, 189, 248, 0.2)', // Sky 400 with opacity
                    'borderColor' => '#38bdf8', // Sky 400 (Bright Blue/Cyan)
                    'pointBackgroundColor' => '#38bdf8',
                    'pointBorderColor' => '#fff',
                    'pointHoverBackgroundColor' => '#fff',
                    'pointHoverBorderColor' => '#38bdf8',
                ]
            ]
        ];
    }
}
