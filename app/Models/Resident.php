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
      ->logExcept(['id', 'created_at', 'updated_at']);
  }

  public function religion(): BelongsTo
  {
    return $this->belongsTo(Religion::class);
  }
}
