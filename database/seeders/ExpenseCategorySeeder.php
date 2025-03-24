<?php

namespace Database\Seeders;

use App\Models\ExpenseCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExpenseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $expenseCategoryData = ['Salary', "Canteen", "Property Maintainence", "Stationary", "Taxes", "Electronics"];
        foreach ($expenseCategoryData as $value) {
            $expenseCategoryObj = new ExpenseCategory();
            $expenseCategoryObj->expenseCategoryName = $value;
            $expenseCategoryObj->save();
        }
    }
}
