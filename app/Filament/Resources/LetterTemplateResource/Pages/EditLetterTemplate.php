<?php

namespace App\Filament\Resources\LetterTemplateResource\Pages;

use App\Filament\Resources\LetterTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLetterTemplate extends EditRecord
{
  protected static string $resource = LetterTemplateResource::class;

  protected function getHeaderActions(): array
  {
    return [
      Actions\DeleteAction::make(),
    ];
  }

  protected function getRedirectUrl(): string
  {
    $resource = static::getResource();
    return $resource::getUrl('index');
  }
}
