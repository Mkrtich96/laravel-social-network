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
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'admin' => true,
            'gender' => 0,
            'password'=> bcrypt('123')
        ]);

        DB::table('users')->insert([
            'name' => 'Mko',
            'email' => 'mkrtich.malkhasyan1996@gmail.com',
            'admin' => false,
            'gender' => 0,
            'password'=> bcrypt('123')
        ]);

        DB::table('users')->insert([
            'name' => 'Gago',
            'email' => 'gagik@gmail.com',
            'admin' => false,
            'gender' => 0,
            'password'=> bcrypt('123')
        ]);

        DB::table('users')->insert([
            'name' => 'Hakob',
            'email' => 'hakobyan@gmail.com',
            'admin' => false,
            'gender' => 0,
            'password'=> bcrypt('123')
        ]);
    }
}
