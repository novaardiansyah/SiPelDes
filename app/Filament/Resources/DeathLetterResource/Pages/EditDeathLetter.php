<?php

namespace App\Filament\Resources\DeathLetterResource\Pages;

use App\Filament\Resources\DeathLetterResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDeathLetter extends EditRecord
{
    protected static string $resource = DeathLetterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
