<?php

namespace Database\Seeders;

use App\Models\TypesPeopleCase;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypesPeopleCasesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TypesPeopleCase::updateOrCreate(
            ['type_id' => '9483073d-97fb-47e7-a126-8a5ce809e568'],
            [
                'name' => 'Temporary residence permit',
            ],
        );
    }
}
