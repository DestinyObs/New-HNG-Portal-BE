<?php

namespace Database\Factories;

use App\Models\JobAttribute;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class JobAttributeFactory extends Factory
{
    protected $model = JobAttribute::class;

    public function definition(): array
    {
        // Admin user for attribute creation
        $admin = User::where('role', 'admin')->first();

        // Sample data groups
        $categories = ['Backend', 'Frontend', 'DevOps', 'Software Engineering', 'UI/UX', 'Data Science'];
        $types = ['Full-time', 'Part-time', 'Contract', 'Internship'];
        $modes = ['Remote', 'Onsite', 'Hybrid'];

        // Pick attribute type
        $attribute_type = $this->faker->randomElement(['category', 'type', 'mode']);

        // Assign name based on group
        $name = match ($attribute_type) {
            'category' => $this->faker->randomElement($categories),
            'type'     => $this->faker->randomElement($types),
            'mode'     => $this->faker->randomElement($modes),
        };

        return [
            'user_id' => $admin?->id,          // Only admin creates attributes
            'name' => $name,
            'slug' => Str::slug($name . '-' . Str::random(5)),
            'attribute_type' => $attribute_type,
            'status' => true,
        ];
    }
}