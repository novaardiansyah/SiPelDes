<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Resident extends Model
{
  use HasFactory, LogsActivity;

  protected $guarded = ['id'];

  protected $casts = [
    'status_perkawinan' => 'boolean',
  ];

  public function getActivitylogOptions(): LogOptions
  {
    return LogOptions::defaults()
      ->logOnly(['nama', 'nik', 'tempat_lahir', 'tanggal_lahir', 'agama', 'alamat', 'status_perkawinan']);
  }

  public function religion(): BelongsTo
  {
    return $this->belongsTo(Religion::class, 'agama');
  }
}
