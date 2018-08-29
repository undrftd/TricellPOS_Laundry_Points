<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        DB::table('users')->insert([
        	'card_number' => rand(),
            'username' => str_random(5),
            'firstname' => str_random(10),
            'lastname' => str_random(10),
            'email' => str_random(10).'@gmail.com',
            'address' => str_random(10),
            'landline' => rand(),
            'current_load' => '0',
            'password' => bcrypt('123')
        ]);
    }
}
