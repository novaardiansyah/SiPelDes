<?php

namespace App\Filament\Resources\LetterTemplateResource\Pages;

use App\Filament\Resources\LetterTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateLetterTemplate extends CreateRecord
{
  protected static string $resource = LetterTemplateResource::class;

  protected function getRedirectUrl(): string
  {
    $resource = static::getResource();
    return $resource::getUrl('index');
  }
}
