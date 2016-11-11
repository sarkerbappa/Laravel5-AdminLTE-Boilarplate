<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('users')->insert([
            'f_name'        => 'Bappa',
            'l_name'        => 'Sarker',
            'image'         => 'blank_image.png',
            'username'      => 'admin',
            'email'         => 'admin@ku.edu',
            'password'      => Hash::make('123456'),
            'role'          => 'admin',
            'status'        => '1'
        ]);
    }
}
