<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create('it_IT');

        DB::table('users')->where('email', 'LIKE', '%ticketisy.com')->delete();

        User::create([
            'name'     => 'Administrator',
            'email'    => 'admin@ticketisy.com',
            'password' => bcrypt('test'),
            'role'     => 'admin',
        ]);

        for ($n = 1; $n <= 30; $n++) {
            User::create([
                'name'     => "{$faker->firstName} {$faker->lastName}",
                'email'    => "tech_{$n}@ticketisy.com",
                'password' => bcrypt('test'),
                'role'     => 'technician',
            ]);
        }
    }
}