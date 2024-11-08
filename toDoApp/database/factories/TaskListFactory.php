<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TaskList>
 */
class TaskListFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title'=>$this->faker->paragraph(1),
            'context'=>$this->faker->paragraph(2),
            'date'=>$this->faker->date(),
            'due_date'=>$this->faker->date(),
        ];
    }
}
