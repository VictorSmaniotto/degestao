<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Documentation extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static string $view = 'filament.pages.documentation';

    protected static ?string $title = 'Documentação & Ajuda';

    protected static ?string $navigationLabel = 'Documentação';

    protected static ?string $slug = 'ajuda';

    protected static ?int $navigationSort = 99; // Exibir por último no menu
}
