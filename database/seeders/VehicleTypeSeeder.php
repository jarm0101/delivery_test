<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\VehicleType;

class VehicleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = ['VehicleTypeA', 'VehicleTypeB', 'VehicleTypeC'];

        foreach ($types as $type) {
            VehicleType::firstOrCreate(['name' => $type]);
        }
    }
}
