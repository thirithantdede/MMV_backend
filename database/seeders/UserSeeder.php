<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = ['userA', 'userB', 'userC', 'userD', 'userE'];

        foreach ($users as $user) {
            \App\Models\User::factory()->create([
                'name' => $user,
                'email' => $user.'@gmail.com',
                "password" => bcrypt('password'),
            ]);
        }
    }
}
