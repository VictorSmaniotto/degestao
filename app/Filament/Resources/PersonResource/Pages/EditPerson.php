<?php

namespace App\Filament\Resources\PersonResource\Pages;

use App\Filament\Resources\PersonResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPerson extends EditRecord
{
    protected static string $resource = PersonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('create_user')
                ->label(fn($record) => $record->user ? 'Acesso Configurado' : 'Criar Acesso')
                ->icon('heroicon-o-key')
                ->color(fn($record) => $record->user ? 'success' : 'warning')
                ->disabled(fn($record) => $record->user !== null)
                ->form([
                    \Filament\Forms\Components\TextInput::make('email')
                        ->label('E-mail de Login')
                        ->email()
                        ->default(fn($record) => $record->email)
                        ->unique('users', 'email')
                        ->required(),
                    \Filament\Forms\Components\TextInput::make('password')
                        ->label('Senha Inicial')
                        ->password()
                        ->default('mudar123')
                        ->required(),
                    \Filament\Forms\Components\Select::make('role')
                        ->label('Perfil de Acesso')
                        ->options([
                            'manager' => 'Gestor (Team Leader)',
                            'employee' => 'Colaborador (Individual Contributor)',
                            'admin' => 'Admin (Acesso Total)'
                        ])
                        ->default('employee')
                        ->required(),
                ])
                ->action(function ($record, array $data) {
                    \App\Models\User::create([
                        'name' => $record->name,
                        'email' => $data['email'],
                        'password' => bcrypt($data['password']),
                        'role' => $data['role'],
                        'person_id' => $record->id,
                    ]);

                    \Filament\Notifications\Notification::make()
                        ->title('Acesso Criado com Sucesso')
                        ->success()
                        ->send();
                }),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            \App\Filament\Resources\PersonResource\Widgets\CompetencyRadarChart::class,
        ];
    }
}
