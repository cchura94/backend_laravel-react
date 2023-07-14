<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $u = new User();
        $u->name = "administrador";
        $u->email = "administrador@mail.com";
        $u->password = "administrador54321";
        $u->save();
    }
}
