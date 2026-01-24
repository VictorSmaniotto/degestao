<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContextResource\Pages;
use App\Filament\Resources\ContextResource\RelationManagers;
use App\Models\Context;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContextResource extends Resource
{
    protected static ?string $model = Context::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\Select::make('complexity_level')
                    ->options([
                        1 => '1 - Baixa',
                        2 => '2 - Média',
                        3 => '3 - Alta',
                        4 => '4 - Muito Alta',
                        5 => '5 - Crítica',
                    ])
                    ->required()
                    ->numeric(),
                Forms\Components\Toggle::make('is_structured')
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
                    ->searchable(),
                Tables\Columns\TextColumn::make('complexity_level')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_structured')
                    ->boolean(),
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
            'index' => Pages\ListContexts::route('/'),
            'create' => Pages\CreateContext::route('/create'),
            'edit' => Pages\EditContext::route('/{record}/edit'),
        ];
    }
}
