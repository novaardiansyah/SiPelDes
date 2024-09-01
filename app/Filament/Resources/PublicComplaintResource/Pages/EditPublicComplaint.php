<?php

namespace App\Filament\Resources\PublicComplaintResource\Pages;

use App\Filament\Resources\PublicComplaintResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPublicComplaint extends EditRecord
{
  protected static string $resource = PublicComplaintResource::class;

  protected function getHeaderActions(): array
  {
    return [
      Actions\DeleteAction::make(),
    ];
  }

  protected function mutateFormDataBeforeFill(array $data): array
  {
    $record = $this->getRecord();
    $data['resident_nik']  = $record->resident->nik;
    $data['resident_nama'] = $record->resident->nama;
    return $data;
  }
}
