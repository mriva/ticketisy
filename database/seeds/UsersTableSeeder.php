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
            'name'      => 'Administrator',
            'email'     => 'admin@ticketisy.com',
            'password'  => bcrypt('test'),
            'role'      => 'admin',
            'api_token' => str_random(60),
        ]);

        for ($n = 1; $n <= 30; $n++) {
            $tech = User::create([
                'name'      => "{$faker->firstName} {$faker->lastName}",
                'email'     => "tech_{$n}@ticketisy.com",
                'password'  => bcrypt('test'),
                'role'      => 'technician',
                'api_token' => str_random(60),
            ]);

            $rand = range(1, 6);
            shuffle($rand);

            for ($i = 1; $i <= 2; $i++) {
                $department_id = array_pop($rand);
                DB::table('users_departments')->insert([
                    'user_id'       => $tech->id,
                    'department_id' => $department_id,
                ]);
            }
        }
    }
}
