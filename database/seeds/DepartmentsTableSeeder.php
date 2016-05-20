<?php

use Illuminate\Database\Seeder;

class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('departments')->truncate();
        DB::table('departments')->insert([
            ['name' => 'Amministrazione'],
            ['name' => 'Pagamenti'],
            ['name' => 'Commerciale'],
            ['name' => 'Assistenza Linux'],
            ['name' => 'Assistenza Windows'],
            ['name' => 'Assistenza server dedicati'],
        ]);
    }
}
