<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('letter_templates', function (Blueprint $table) {
      $table->id();
      $table->string('judul')->nullable();
      $table->string('kode')->nullable();
      $table->integer('nomor')->default(1);
      $table->string('pejabat')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('letter_templates');
  }
};
