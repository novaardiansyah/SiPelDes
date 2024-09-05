<?php

namespace App\Filament\Resources\ResidentLetterResource\Pages;

use App\Filament\Resources\ResidentLetterResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListResidentLetters extends ListRecords
{
    protected static string $resource = ResidentLetterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
