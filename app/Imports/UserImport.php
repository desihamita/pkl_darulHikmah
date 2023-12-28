<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UserImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection){
        foreach ($collection as $row) {
            if (!empty($row['name'])) {
                User::insert([
                    'name' => $row['name'],
                    'nis' => $row['nis'],
                    'email' => $row['email'],
                    'password' => Hash::make($row['password']),
                ]);
            }
        }
    }
}