<?php

namespace Database\Seeders;

use App\Models\ExpenseType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExpenseTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $expenseTypeData = ['Direct', "Indirect"];
        foreach ($expenseTypeData as $value) {
            $expenseTypeObj = new ExpenseType();
            $expenseTypeObj->expenseTypeName = $value;
            $expenseTypeObj->save();
        }
    }
}
