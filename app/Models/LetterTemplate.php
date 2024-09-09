<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class LetterTemplate extends Model
{
  use HasFactory, LogsActivity;

  protected $guarded = ['id'];

  public const PENGAJUAN = 'pengajuan';
  public const DISETUJUI = 'disetujui';
  public const DITOLAK = 'ditolak';
  
  public function getActivitylogOptions(): LogOptions
  {
    return LogOptions::defaults()
      ->logOnly(['judul', 'kode', 'nomor', 'pejabat']);
  }
}
