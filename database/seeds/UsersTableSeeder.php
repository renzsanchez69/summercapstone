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
            'name' => str_random(10),
            'email' => 'admin@gmail.com',
            'password' => bcrypt('root'),
            'username' => 'admin',
            'date_of_birth' => '1990-01-05',
            'gender' => 'Male',
            'phone_number' => 1234,
            'address' => 'Philippines',
            'is_admin' => true
        ]);

        $username = 'test';
        DB::table('users')->insert([
            'name' => 'vanessa',
            'email' => $username.'@gmail.com',
            'password' => bcrypt('secret'),
            'username' => $username,
            'date_of_birth' => '1994-09-18',
            'gender' => 'Female',
            'phone_number' => 72107949,
            'address' => 'Port Moresby, Papua New Guinea'
        ]);

        $username = 'test1';
        DB::table('users')->insert([
            'name' => 'veronica',
            'email' => $username.'@gmail.com',
            'password' => bcrypt('secret'),
            'username' => $username,
            'date_of_birth' => '1993-10-16',
            'gender' => 'Female',
            'phone_number' => 72602113,
            'address' => 'Eastern Highlands, Papua New Guinea'
        ]);

        $username = 'test2';
        DB::table('users')->insert([
            'name' => 'klein',
            'email' => $username.'@gmail.com',
            'password' => bcrypt('secret'),
            'username' => $username,
            'date_of_birth' => '2019-02-05',
            'gender' => 'Male',
            'phone_number' => 1234,
            'address' => 'Philippines'
        ]);
    }
}
