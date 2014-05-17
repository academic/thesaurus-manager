<?php

class UsersTableSeeder extends Seeder {

    public function run() {
        DB::table('users')->delete();

        $users = array(
            array(
                'email' => 'user@localhost.com',
                'password' => Hash::make('user'),
                'first_name' => 'User',
                'last_name' => 'User',
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            )
        );

        DB::table('users')->insert($users);
    }

}
