<?php

namespace App\Filament\Resources\RelocationLetterResource\Pages;

use App\Filament\Resources\RelocationLetterResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRelocationLetters extends ListRecords
{
    protected static string $resource = RelocationLetterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
