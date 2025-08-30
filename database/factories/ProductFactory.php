<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
        'name' => $this->faker->words(3, true),
        'category' => $this->faker->randomElement([
      
    'Feeds',
    'Supplements',
    'Medications',
    'Vitamins',

    'Poultry Equipments',
    'Cages',
    'Drinkers',
    'Feeders',
    'Incubators',
    'Brooders',
    'Water Systems',

    'Veterinary Supplies',
    'Farm Tools',
    'Seeds',
    'Fertilizers',
    'Pesticides',
    'Herbicides',

    
    'Machinery',
    'Tractors',
    'Harvesting Tools',
    'Irrigation Systems',

    'Housing Materials',
    'Nets',
    'Pipes',
    'Storage Bins',


    'Chicks',
    'Day-old Layers',
    'Day-old Broilers',
    'Organic Products'
]),

        'description' => $this->faker->sentence(3, true),
        'date_time' => $this->faker->dateTimeThisYear(),
        ];
    }
}
