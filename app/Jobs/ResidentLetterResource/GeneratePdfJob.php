<?php

namespace App\Jobs\ResidentLetterResource;

use App\Models\ResidentLetter;
use Carbon\Carbon;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class GeneratePdfJob implements ShouldQueue
{
  use Queueable;
  public $data;

  /**
   * Create a new job instance.
   */
  public function __construct(
    public ResidentLetter $record,
    public Model $user
  ) { 
    $this->data['now'] = now()->translatedFormat('d F Y');
  }

  /**
   * Execute the job.
   */
  public function handle(): void
  {
    $data = $this->data;
    $record = $this->record;

    $tempat_lahir = $record->resident?->tempat_lahir;
    $tanggal_lahir = $record->resident?->tanggal_lahir ? Carbon::parse($record->resident->tanggal_lahir)->translatedFormat('d F Y') : '';
    $record->ttl = $tempat_lahir && $tanggal_lahir ? "$tempat_lahir, $tanggal_lahir" : '';

    $kode_surat = $record->kode_surat;
    $filepath   = $record->file_surat;

    $mpdf = new \Mpdf\Mpdf();
    $mpdf->WriteHTML(view('pdf.ResidentLetterResource.letter', compact('data', 'record')));
    $mpdf->Output(storage_path("app/public/{$filepath}"), 'F');

    $download = explode('/', $filepath)[array_key_last(explode('/', $filepath))];

    Notification::make()
      ->title('Surat Keterangan Penduduk ('.$kode_surat.')')
      ->body('File surat Anda siap untuk didownload.')
      ->icon('heroicon-o-arrow-down-tray')
      ->iconColor('success')
      ->actions([
        Action::make('download')
          ->url(asset("storage/{$filepath}"))
          ->extraAttributes(['download' => $download])
          ->markAsRead()
          ->button()
      ])
      ->sendToDatabase($this->user);

    Log::info(__METHOD__.':'.__LINE__, ['message' => 'The job has finished executing.']);
  }
}
