<?php

namespace App\Filament\Resources\RelocationLetterResource\Pages;

use App\Filament\Resources\RelocationLetterResource;
use App\Models\RelocationLetter;
use App\Models\Resident;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateRelocationLetter extends CreateRecord
{
  protected static string $resource = RelocationLetterResource::class;

  protected function getRedirectUrl(): string
  {
    $resource = static::getResource();
    return $resource::getUrl('index');
  }

  protected function mutateFormDataBeforeCreate(array $data): array
  {
    $resident = Resident::where('nik', $data['nik'])->first();
    
    if (!$resident) {
      Notification::make()
        ->title('Terjadi kesalahan!')
        ->body('NIK data penduduk tidak ditemukan atau belum terdaftar.')
        ->danger()
        ->send();

      $this->halt();
    }

    $data['user_id'] = auth()->user()->id;
    $data['resident_id'] = $resident->id;
    $data['status'] = RelocationLetter::PENGAJUAN;

    return $data;
  }
}
