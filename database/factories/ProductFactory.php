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
    public function definition(): array //factor for seeder i set it like a angriculture equipments
    {
        $categories = [
            'Feeds', 'Supplements', 'Medications', 'Vitamins',
            'Poultry Equipments', 'Cages', 'Drinkers', 'Feeders',
            'Incubators', 'Brooders', 'Water Systems',
            'Veterinary Supplies', 'Farm Tools', 'Seeds', 'Fertilizers', 
            'Pesticides', 'Herbicides', 'Machinery', 'Tractors', 
            'Harvesting Tools', 'Irrigation Systems', 'Housing Materials',
            'Nets', 'Pipes', 'Storage Bins', 'Chicks', 'Day-old Layers',
            'Day-old Broilers', 'Organic Products'
        ];

        $category = $this->faker->randomElement($categories);
        $name = ucfirst($this->faker->word()) . ' ' . $category;

        $description = match($category) {
            'Feeds' => "Nutritious feed designed for healthy poultry growth.",
            'Supplements' => "Essential supplements to boost poultry immunity.",
            'Medications' => "Safe medications for common poultry illnesses.",
            'Vitamins' => "High-quality vitamins for optimal bird health.",
            'Poultry Equipments' => "Durable poultry equipment for farm efficiency.",
            'Cages' => "Sturdy cages for housing poultry safely.",
            'Drinkers' => "Efficient drinkers to ensure clean water supply.",
            'Feeders' => "Practical feeders to reduce feed wastage.",
            'Incubators' => "Reliable incubators to hatch healthy chicks.",
            'Brooders' => "Brooders that maintain optimal warmth for chicks.",
            'Water Systems' => "Automated water systems for convenience.",
            'Veterinary Supplies' => "Essential veterinary supplies for farm care.",
            'Farm Tools' => "High-quality tools for daily farm operations.",
            'Seeds' => "Fresh seeds for crop and poultry feed production.",
            'Fertilizers' => "Effective fertilizers for optimal plant growth.",
            'Pesticides' => "Safe pesticides to protect crops naturally.",
            'Herbicides' => "Efficient herbicides to control unwanted plants.",
            'Machinery' => "Reliable machinery to ease farm work.",
            'Tractors' => "Powerful tractors for versatile farm tasks.",
            'Harvesting Tools' => "Durable tools to speed up harvest time.",
            'Irrigation Systems' => "Efficient irrigation systems for uniform watering.",
            'Housing Materials' => "Materials to build secure poultry houses.",
            'Nets' => "Protective nets to keep poultry safe.",
            'Pipes' => "Durable pipes for water and feed systems.",
            'Storage Bins' => "Reliable bins to store feed and supplies.",
            'Chicks' => "Healthy day-old chicks ready for rearing.",
            'Day-old Layers' => "Young layer chicks for egg production.",
            'Day-old Broilers' => "Young broiler chicks for meat production.",
            'Organic Products' => "Certified organic products for farm use.",
            default => $this->faker->sentence(8, true),
        };

        return [
            'name' => $name,
            'category' => $category,
            'description' => $description,
            'date_time' => $this->faker->dateTimeThisYear(),
        ];
    }
}
