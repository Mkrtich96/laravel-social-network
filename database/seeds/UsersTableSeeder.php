<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run(){
        DB::table('users')->insert([
            'name' => 'Mko',
            'email' => 'mkrtich.malkhasyan1996@gmail.com',
            'gender' => 0,
            'password'=> bcrypt('password')
        ]);

        DB::table('users')->insert([
            'name' => 'Gago',
            'email' => 'gagik@gmail.com',
            'gender' => 0,
            'password'=> bcrypt('password')
        ]);

        DB::table('users')->insert([
            'name' => 'Hakob',
            'email' => 'hakobyan@gmail.com',
            'gender' => 0,
            'password'=> bcrypt('password')
        ]);

    }
}
