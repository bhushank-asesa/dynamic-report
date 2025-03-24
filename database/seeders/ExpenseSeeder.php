<?php

namespace Database\Seeders;

use App\Models\Expense;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locationData = [
            ["mainLocationId" => 1, "subLocationId" => 1],
            ["mainLocationId" => 1, "subLocationId" => 2],
            ["mainLocationId" => 1, "subLocationId" => 3],
            ["mainLocationId" => 2, "subLocationId" => 4],
            ["mainLocationId" => 3, "subLocationId" => 5]
        ];
        $expenseType = 2;
        $expenseCategory = [2, 3, 4, 6];
        $title = ["Canteen", "Property Maintainence", "Stationary", "Electronics"];

        for ($i = 0; $i < 1; $i++) {
            $selectedLocation = $locationData[rand(1, 5) - 1];
            $selectedExpenseCategoryIndex = rand(1, 4) - 1;
            $selectedExpenseCategory = $expenseCategory[$selectedExpenseCategoryIndex];
            $selectedTitle = $title[$selectedExpenseCategoryIndex];
            $amount = rand(100, 2000);
            $transactionId = Str::random(5) . rand(1000, 9999);

            $expense = new Expense();
            $expense->mainLocationId = $selectedLocation['mainLocationId'];
            $expense->subLocationId = $selectedLocation['subLocationId'];
            $expense->expenseTypeId = $expenseType;
            $expense->expenseCategoryId = $selectedExpenseCategory;
            $expense->title = $selectedTitle;
            $expense->amount = $amount;
            $expense->transactionId = $transactionId;
            $expense->note = "";
            $expense->dateOfExpense = "2024-" . rand(1, 12) . "-" . rand(1, 28);
            // $expense->save();
        }
        $expenseType = 1;
        $expenseCategory = 5;
        $title = "Tax";
        for ($i = 0; $i < 1; $i++) {
            $selectedLocation = $locationData[rand(1, 5) - 1];
            $amount = rand(100, 2000);
            $transactionId = Str::random(5) . rand(1000, 9999);

            $expense = new Expense();
            $expense->mainLocationId = $selectedLocation['mainLocationId'];
            $expense->subLocationId = $selectedLocation['subLocationId'];
            $expense->expenseTypeId = $expenseType;
            $expense->expenseCategoryId = $expenseCategory;
            $expense->title = $title;
            $expense->amount = $amount;
            $expense->transactionId = $transactionId;
            $expense->note = "";
            $expense->dateOfExpense = "2024-" . rand(1, 12) . "-" . rand(1, 28);
            // $expense->save();
        }
        $expenseType = 1;
        $expenseCategory = 1;
        $title = "Salary";
        for ($i = 0; $i < 100; $i++) {
            $selectedLocation = $locationData[rand(1, 5) - 1];
            $amount = rand(10000, 20000);
            $userId = rand(1, 75);
            $transactionId = Str::random(5) . rand(1000, 9999);

            $expense = new Expense();
            $expense->mainLocationId = $selectedLocation['mainLocationId'];
            $expense->subLocationId = $selectedLocation['subLocationId'];
            $expense->expenseTypeId = $expenseType;
            $expense->expenseCategoryId = $expenseCategory;
            $expense->title = $title;
            $expense->amount = $amount;
            $expense->userId = $userId;
            $expense->transactionId = $transactionId;
            $expense->note = "";
            $dateOfExpense = "2024-" . rand(1, 12) . "-28";
            $expense->dateOfExpense = $dateOfExpense;

            $existingSalary = Expense::where("userId", $userId)->where("dateOfExpense", $dateOfExpense)->first();
            if (!$existingSalary)
                $expense->save();
        }
    }
}
