<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PublicComplaint>
 */
class PublicComplaintFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'resident_id' => fake()->numberBetween(1, 300),
      'description' => fake()->sentence(),
      'attachment' => [fake()->imageUrl(), fake()->imageUrl()],
    ];
  }
}
