<?php

namespace App\Filament\Resources\RelocationLetterResource\Pages;

use App\Filament\Resources\RelocationLetterResource;
use App\Jobs\RelocationLetterResource\GeneratePdfJob;
use App\Models\LetterTemplate;
use App\Models\RelocationLetter;
use App\Models\Resident;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class EditRelocationLetter extends EditRecord
{
  protected static string $resource = RelocationLetterResource::class;

  protected function getHeaderActions(): array
  {
    return [
      Actions\Action::make('tolak')
        ->label('Tolak')
        ->color('danger')
        ->action(function (RelocationLetter $record):void {
          $record->update([
            'status'     => RelocationLetter::DITOLAK,
            'kode_surat' => null,
            'file_surat' => null,
          ]);

        })
        ->requiresConfirmation()
        ->modalHeading('Tolak Surat Keterangan Pindah')
        ->after(fn() => $this->afterAction(bodyNotification: 'Surat keterangan pindah berhasil ditolak.'))
        ->visible(fn (RelocationLetter $record): bool => $record->status === RelocationLetter::PENGAJUAN),

      Actions\Action::make('setujui')
        ->label('Setujui')
        ->color('success')
        ->action(function (RelocationLetter $record):void {
          $template = LetterTemplate::find(2)->first();

          $kode_surat = $template->kode . '/' . now()->translatedFormat('Y-m') . '/' . $template->nomor;

          $filename = 'letter-'. str_replace('/', '-', $kode_surat) .'.pdf';
          $filepath = "pdf/RelocationLetterResource/{$filename}";

          $record->update([
            'status'     => RelocationLetter::DISETUJUI,
            'kode_surat' => $kode_surat,
            'file_surat' => $filepath
          ]);

          $template->increment('nomor');

          GeneratePdfJob::dispatch(user: $record->user, record: $record);
        })
        ->requiresConfirmation()
        ->after(fn() => $this->afterAction(bodyNotification: 'Notifikasi akan dikirim setelah selesai.', titleNotification: 'Surat segera diproses!'))
        ->modalHeading('Setujui Surat Keterangan Pindah')
        ->visible(fn (RelocationLetter $record): bool => $record->status === RelocationLetter::PENGAJUAN),
    ];
  }

  protected function afterAction(string $bodyNotification, string $titleNotification = 'Proses berhasil!')
  {
    Notification::make()
      ->title($titleNotification)
      ->body($bodyNotification)
      ->success()
      ->send();

    $this->redirect($this->getResource()::getUrl('index'));
  }

  protected function getRedirectUrl(): string
  {
    $resource = static::getResource();
    return $resource::getUrl('index');
  }

  protected function mutateFormDataBeforeFill(array $data): array
  {
    $record = $this->getRecord();

    $tempat_lahir = $record->resident?->tempat_lahir;
    $tanggal_lahir = $record->resident?->tanggal_lahir ? Carbon::parse($record->resident->tanggal_lahir)->translatedFormat('d F Y') : '';

    $data['nik'] = $record->resident->nik;
    $data['nama'] = $record->resident->nama;
    $data['tempat_lahir'] = $tempat_lahir && $tanggal_lahir ? "$tempat_lahir, $tanggal_lahir" : '';
    $data['diajukan_oleh'] = $record->user->name;

    return $data;
  }

  protected function mutateFormDataBeforeSave(array $data): array
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

    $data['resident_id'] = $resident->id;
    return $data;
  }
}
