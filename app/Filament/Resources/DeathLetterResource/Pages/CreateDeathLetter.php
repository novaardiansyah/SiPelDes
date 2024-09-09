<?php

namespace App\Filament\Resources\DeathLetterResource\Pages;

use App\Filament\Resources\DeathLetterResource;
use App\Models\LetterTemplate;
use App\Models\Resident;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateDeathLetter extends CreateRecord
{
  protected static string $resource = DeathLetterResource::class;

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
    $data['status'] = LetterTemplate::PENGAJUAN;

    return $data;
  }
}
