<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class RelocationLetter extends Model
{
  use HasFactory, LogsActivity;

  protected $guarded = ['id'];

  public const PENGAJUAN = 'pengajuan';
  public const DISETUJUI = 'disetujui';
  public const DITOLAK = 'ditolak';
  
  public function getActivitylogOptions(): LogOptions
  {
    return LogOptions::defaults()
      ->logOnly(['resident_id', 'user_id', 'status', 'jumlah_keluarga', 'kode_surat', 'file_surat', 'alamat_asal', 'alamat_tujuan']);
  }

  public function resident(): BelongsTo
  {
    return $this->belongsTo(Resident::class);
  }

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }
}
