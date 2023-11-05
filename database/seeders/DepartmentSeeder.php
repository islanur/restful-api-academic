<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Department::create([
            'code' => 'SI-051',
            'name' => 'Sistem Informasi',
            'alias' => 'SI'
        ]);
        Department::create([
            'code' => 'SK-052',
            'name' => 'Sistem Komputer',
            'alias' => 'SK'
        ]);
    }
}
