<?php

namespace Database\Seeders;

use App\Models\SubLocation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subLocationData = [
            ["mainLocationId" => 1, "subLocationName" => 'Mumbai'],
            ["mainLocationId" => 1, "subLocationName" => 'Navi Mumbai'],
            ["mainLocationId" => 1, "subLocationName" => 'Panvel'],
            ["mainLocationId" => 2, "subLocationName" => 'Nashik'],
            ["mainLocationId" => 3, "subLocationName" => 'Nagar']
        ];
        foreach ($subLocationData as $row) {
            $subLocationObj = new SubLocation();
            $subLocationObj->subLocationName = $row['subLocationName'];
            $subLocationObj->mainLocationId = $row['mainLocationId'];
            $subLocationObj->save();
        }
    }
}
