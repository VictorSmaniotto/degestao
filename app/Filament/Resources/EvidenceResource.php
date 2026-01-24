<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EvidenceResource\Pages;
use App\Filament\Resources\EvidenceResource\RelationManagers;
use App\Models\Evidence;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EvidenceResource extends Resource
{
    protected static ?string $model = Evidence::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('person_id')
                    ->relationship('person', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('context_id')
                    ->relationship('context', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('cycle_id')
                    ->relationship('cycle', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('type')
                    ->options([
                        'PERFORMANCE' => 'Performance',
                        'POTENTIAL' => 'Potencial',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('dimension')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('intensity')
                    ->options([
                        0 => '0 - Não observado',
                        1 => '1 - Observado com apoio',
                        2 => '2 - Observado com autonomia',
                        3 => '3 - Observado consistentemente',
                        4 => '4 - Observado em complexidade',
                    ])
                    ->required()
                    ->numeric(),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\DatePicker::make('occurred_at')
                    ->required()
                    ->default(now()),
                Forms\Components\Hidden::make('recorded_by'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('person.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('context.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cycle.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('dimension')
                    ->searchable(),
                Tables\Columns\TextColumn::make('intensity')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('occurred_at')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('recorded_by')
                    ->numeric()
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
            'index' => Pages\ListEvidence::route('/'),
            'create' => Pages\CreateEvidence::route('/create'),
            'edit' => Pages\EditEvidence::route('/{record}/edit'),
        ];
    }
}
