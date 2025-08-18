<?php

namespace Database\Seeders;

use App\Models\InpolAccount;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InpolAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accounts = [
            'ok.ilchukk@gmail.com',
            'biuro.ilchuk9@gmail.com',
            'turno8839@gmail.com',
        ];

        foreach ($accounts as $email) {
            InpolAccount::updateOrCreate(
                ['email' => $email],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
