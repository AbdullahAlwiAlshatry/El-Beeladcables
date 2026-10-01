<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class AdminSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'email' => 'AbdullahAlawi@Admin.gmail.net.com',
            'password' => Hash::make('123'),
        ]);
    }
}