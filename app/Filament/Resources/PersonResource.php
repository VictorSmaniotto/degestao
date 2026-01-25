<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PersonResource\Pages;
use App\Filament\Resources\PersonResource\RelationManagers;
use App\Models\Person;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PersonResource extends Resource
{
    protected static ?string $model = Person::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $modelLabel = 'Colaborador';
    protected static ?string $pluralModelLabel = 'Colaboradores';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nome Completo')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->label('E-mail Corporativo')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('role')
                    ->label('Cargo')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('admitted_at')
                    ->label('Data de Admissão')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('E-mail')
                    ->searchable(),
                Tables\Columns\TextColumn::make('role')
                    ->label('Cargo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('admitted_at')
                    ->label('Admissão')
                    ->date()
                    ->sortable(),
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
                Tables\Actions\Action::make('calculate_9box')
                    ->label('Calcular 9Box')
                    ->icon('heroicon-o-calculator')
                    ->form([
                        Forms\Components\Select::make('cycle_id')
                            ->label('Ciclo')
                            ->options(\App\Models\Cycle::all()->pluck('name', 'id'))
                            ->required(),
                    ])
                    ->action(function (Person $record, array $data) {
                        $cycle = \App\Models\Cycle::find($data['cycle_id']);
                        $aggregator = app(\App\Domains\Aggregation\Services\EvidenceAggregator::class);
                        $result = $aggregator->calculate($record, $cycle);

                        \Filament\Notifications\Notification::make()
                            ->title("Quadrante: {$result->quadrantLabel}")
                            ->body("Performance: {$result->x}% | Potencial: {$result->y}% (Baseado em {$result->evidenceCount} evidências)")
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPeople::route('/'),
            'create' => Pages\CreatePerson::route('/create'),
            'edit' => Pages\EditPerson::route('/{record}/edit'),
        ];
    }
}
