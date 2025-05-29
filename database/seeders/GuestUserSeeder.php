<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GuestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $guestUsers = ["guest-userA@gmail.com","guest-userB@gmail.com"];

        foreach ($guestUsers as $guestUser) {
            \App\Models\GuestUser::factory()->create([
                'email' => $guestUser,
            ]);
        }
    }
}
