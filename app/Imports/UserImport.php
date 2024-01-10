<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Kelas;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UserImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection){
        foreach ($collection as $row) {
            $kelas = Kelas::where('class', $row['kelas'])->first();

            if ($kelas != null) {
                User::create([
                    'name' => $row['nama'],
                    'nis' => $row['nis'],
                    'email' => $row['email'],
                    'password' => Hash::make($row['password']),
                    'kelas_id' => $kelas['id']
                ]);
            } else {
                info('Kelas not found for: ' . $row['kelas']);
            }
        }
    }
}