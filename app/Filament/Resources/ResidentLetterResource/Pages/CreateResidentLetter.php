<?php

namespace App\Filament\Resources\ResidentLetterResource\Pages;

use App\Filament\Resources\ResidentLetterResource;
use App\Models\Resident;
use App\Models\ResidentLetter;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateResidentLetter extends CreateRecord
{
  protected static string $resource = ResidentLetterResource::class;

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
    $data['status'] = ResidentLetter::PENGAJUAN;

    return $data;
  }
}
