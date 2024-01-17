<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;


class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User;
        $user->id = (string) Str::uuid();
        $user->name = 'Admin';
        $user->email = 'admin@cbt.com';
        $user->password = Hash::make('admin123');
        $user->is_admin = true;
        $user->nis = null;
        $user->kelas_id = null;
        $user->save();
    }
}