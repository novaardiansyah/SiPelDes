<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class PublicComplaint extends Model
{
  use HasFactory, LogsActivity;

  protected $guarded = ['id'];

  protected $casts = [
    'attachment' => 'array',
  ];

  public function getActivitylogOptions(): LogOptions
  {
    return LogOptions::defaults()
      ->logOnly(['description', 'attachment']);
  }

  public function resident(): BelongsTo
  {
    return $this->belongsTo(Resident::class);
  }
}
