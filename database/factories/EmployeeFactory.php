<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $departments = ['IT', 'HR', 'Finance', 'Marketing', 'Operations', 'Sales', 'Legal', 'R&D'];
        $positions = ['Manager', 'Senior Staff', 'Staff', 'Junior Staff', 'Intern', 'Director', 'Head'];
        $genders = ['Laki-laki', 'Perempuan'];
        $statuses = ['Aktif', 'Non-Aktif'];

        return [
            'nik' => fake()->unique()->numerify('EMP##########'),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'gender' => fake()->randomElement($genders),
            'birth_date' => fake()->date('Y-m-d', '-18 years'),
            'birth_place' => fake()->city(),
            'address' => fake()->address(),
            'department' => fake()->randomElement($departments),
            'position' => fake()->randomElement($positions),
            'join_date' => fake()->date('Y-m-d', 'now'),
            'salary' => fake()->randomFloat(2, 3000000, 50000000),
            'status' => fake()->randomElement($statuses),
        ];
    }
}
