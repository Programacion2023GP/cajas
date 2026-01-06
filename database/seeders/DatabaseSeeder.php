<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@gomezpalacio.gob.mx',
                'password' => Hash::make('sadmin')
            ],
            [
                'name' => 'Caja 1',
                'email' => 'caja1@gomezpalacio.gob.mx',
                'password' => Hash::make('12345678'),
            ],
            [
                'name' => 'Caja 2',
                'email' => 'caja2@gomezpalacio.gob.mx',
                'password' => Hash::make('12345678'),
            ],
            [
                'name' => 'Caja 3',
                'email' => 'caja3@gomezpalacio.gob.mx',
                'password' => Hash::make('12345678'),
            ],
            [
                'name' => 'Caja 4',
                'email' => 'caja4@gomezpalacio.gob.mx',
                'password' => Hash::make('12345678'),
            ],
            [
                'name' => 'Caja 5',
                'email' => 'caja5@gomezpalacio.gob.mx',
                'password' => Hash::make('12345678'),
            ],
            [
                'name' => 'Caja 6',
                'email' => 'caja6@gomezpalacio.gob.mx',
                'password' => Hash::make('12345678'),
            ],
        ];

        $data = array_map(function ($user) {
            return [
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => $user['password'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, $users);

        DB::table('users')->insert($data);
    }
}