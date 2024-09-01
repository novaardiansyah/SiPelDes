<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Resident>
 */
class ResidentFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'nama'              => fake()->name(),
      'nik'               => fake()->unique()->nik(),
      'tempat_lahir'      => fake()->city(),
      'tanggal_lahir'     => fake()->date(),
      'agama'             => fake()->randomElement([1, 2, 3, 4, 5, 6]),
      'jenis_kelamin'     => fake()->randomElement(['Laki-laki', 'Perempuan']),
      'status_perkawinan' => fake()->boolean(),
      'pekerjaan'         => fake()->jobTitle(),
      'status_penduduk'   => fake()->randomElement([1, 2]),
      'alamat'            => fake()->address(),
    ];
  }
}
