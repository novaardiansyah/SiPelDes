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
    Schema::create('death_letters', function (Blueprint $table) {
      $table->id();
      $table->foreignId('resident_id')->constrained('residents')->cascadeOnDelete();
      $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
      $table->enum('status', ['pengajuan', 'disetujui', 'ditolak'])->default('pengajuan');
      $table->string('kode_surat')->nullable();
      $table->string('file_surat')->nullable();
      $table->string('penyebab_kematian')->nullable();
      $table->string('tempat_kematian')->nullable();
      $table->dateTime('waktu_kematian')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('death_letters');
  }
};
