<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $modelLabel = 'Usuário';

    // Mostra apenas para Admin (futuro RBAC)
    protected static ?string $navigationGroup = 'Configurações';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nome')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->label('E-mail')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('role')
                    ->label('Função do Sistema')
                    ->options([
                        'admin' => 'Administrador (Tudo)',
                        'manager' => 'Gestor (Time)',
                        'employee' => 'Colaborador (Pessoal)',
                    ])
                    ->required()
                    ->default('employee'),
                Forms\Components\Select::make('person_id')
                    ->label('Vincular Colaborador')
                    ->relationship('person', 'name', modifyQueryUsing: function (Builder $query, ?User $record) {
                        // Mostra pessoas sem usuário vinculado OU a pessoa já vinculada a este registro (edição)
                        return $query->whereDoesntHave('user')
                            ->when($record?->person_id, fn($q) => $q->orWhere('id', $record->person_id));
                    })
                    ->searchable()
                    ->preload()
                    ->helperText('Selecione quem é este usuário na estrutura da empresa.')
                    ->required(fn(Forms\Get $get) => $get('role') !== 'admin')
                    ->validationMessages([
                        'required' => 'Para usuários não-admin, é obrigatório vincular a um colaborador.',
                    ])
                    ->live()
                    ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                        if ($state && !$get('email')) {
                            $person = \App\Models\Person::find($state);
                            if ($person) {
                                $set('email', $person->email);
                            }
                        }
                    }),
                Forms\Components\TextInput::make('password')
                    ->label('Senha')
                    ->password()
                    ->dehydrated(fn($state) => filled($state))
                    ->required(fn(string $context): bool => $context === 'create')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('role')
                    ->label('Função')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'admin' => 'danger',
                        'manager' => 'warning',
                        'employee' => 'info',
                        default => 'gray',
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('person.name')
                    ->label('Colaborador Vinculado')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageUsers::route('/'),
        ];
    }
}
