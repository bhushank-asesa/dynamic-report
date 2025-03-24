<?php

namespace Database\Seeders;

use App\Models\MainLocation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MainLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mainLocationData = ['Mumbai', "Nashik", "Nagar"];
        foreach ($mainLocationData as $value) {
            $mainLocationObj = new MainLocation();
            $mainLocationObj->mainLocationName = $value;
            $mainLocationObj->save();
        }
    }
}
