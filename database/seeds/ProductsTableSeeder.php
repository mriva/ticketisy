<?php

use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create('it_IT');

        DB::table('products')->truncate();
        DB::table('products')->insert([
            ['title' => 'Hosting condiviso Windows', 'description' => $faker->paragraph],
            ['title' => 'Hosting condiviso Linux', 'description' => $faker->paragraph],
            ['title' => 'Cloud server easy', 'description' => $faker->paragraph],
            ['title' => 'Cloud server pro', 'description' => $faker->paragraph],
            ['title' => 'Cloud server enterprise', 'description' => $faker->paragraph],
            ['title' => 'Dedicated server base', 'description' => $faker->paragraph],
            ['title' => 'Dedicated server elite', 'description' => $faker->paragraph],
        ]);
    }
}
