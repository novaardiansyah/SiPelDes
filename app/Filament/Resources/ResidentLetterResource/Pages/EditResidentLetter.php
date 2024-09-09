<?php

namespace App\Filament\Resources\ResidentLetterResource\Pages;

use App\Filament\Resources\ResidentLetterResource;
use App\Jobs\ResidentLetterResource\GeneratePdfJob;
use App\Models\LetterTemplate;
use App\Models\ResidentLetter;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditResidentLetter extends EditRecord
{
  protected static string $resource = ResidentLetterResource::class;

  protected function getHeaderActions(): array
  {
    return [
      Actions\Action::make('download')
        ->label('Download Surat')
        ->color('primary')
        ->icon('heroicon-o-arrow-down-tray')
        ->visible(function (ResidentLetter $record): bool {
          return $record->status !== ResidentLetter::PENGAJUAN && Storage::disk('public')->exists($record->file_surat ?? 'default.pdf');
        })
        ->url(fn(ResidentLetter $record): string => asset('storage/' . $record->file_surat))
        ->openUrlInNewTab(),

      Actions\Action::make('tolak')
        ->label('Tolak')
        ->color('danger')
        ->action(function (ResidentLetter $record):void {
          $record->update([
            'status'     => ResidentLetter::DITOLAK,
            'kode_surat' => null,
            'file_surat' => null,
          ]);
        })
        ->requiresConfirmation()
        ->modalHeading('Tolak Surat Keterangan Pindah')
        ->after(fn() => $this->afterAction(bodyNotification: 'Surat keterangan pindah berhasil ditolak.'))
        ->visible(fn (ResidentLetter $record): bool => $record->status === ResidentLetter::PENGAJUAN),

      Actions\Action::make('setujui')
        ->label('Setujui')
        ->color('success')
        ->action(function (ResidentLetter $record):void {
          $template = LetterTemplate::find(2);

          $kode_surat = $template->kode . '/' . now()->translatedFormat('Y-m') . '/' . $template->nomor;

          $filename = 'letter-'. str_replace('/', '-', $kode_surat) .'.pdf';
          $filepath = "pdf/ResidentLetterResource/{$filename}";

          $record->update([
            'status'     => ResidentLetter::DISETUJUI,
            'kode_surat' => $kode_surat,
            'file_surat' => $filepath
          ]);

          $template->increment('nomor');

          GeneratePdfJob::dispatch(user: $record->user, record: $record);
        })
        ->requiresConfirmation()
        ->after(fn() => $this->afterAction(bodyNotification: 'Notifikasi akan dikirim setelah selesai.', titleNotification: 'Surat segera diproses!'))
        ->modalHeading('Setujui Surat Keterangan Pindah')
        ->visible(fn (ResidentLetter $record): bool => $record->status === ResidentLetter::PENGAJUAN),
    ];
  }

  protected function afterAction(string $bodyNotification, string $titleNotification = 'Proses berhasil!')
  {
    Notification::make()
      ->title($titleNotification)
      ->body($bodyNotification)
      ->success()
      ->send();

    $this->redirect($this->getResource()::getUrl('edit', ['record' => $this->getRecord()]));
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
    $data['jenis_kelamin'] = $record->resident->jenis_kelamin;
    $data['alamat'] = $record->resident->alamat;
    $data['tempat_lahir'] = $tempat_lahir && $tanggal_lahir ? "$tempat_lahir, $tanggal_lahir" : '';
    $data['diajukan_oleh'] = $record->user->name;

    return $data;
  }
}
