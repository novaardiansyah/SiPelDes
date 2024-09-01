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
    Schema::create('residents', function (Blueprint $table) {
      $table->id();
      $table->string('nama')->nullable();
      $table->string('nik')->unique()->nullable();
      $table->string('tempat_lahir')->nullable();
      $table->date('tanggal_lahir')->nullable();
      $table->foreignId('agama')->constrained('religions')->nullable();
      $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->nullable();
      $table->boolean('status_perkawinan')->default(false);
      $table->string('pekerjaan')->nullable();
      $table->integer('status_penduduk')->default(1);
      $table->string('alamat')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('residents');
  }
};
