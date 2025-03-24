<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId("mainLocationId")->nullable()->references("id")->on("main_locations");
            $table->foreignId("subLocationId")->nullable()->references("id")->on("sub_locations");
            $table->foreignId("expenseTypeId")->nullable()->references("id")->on("expense_types");
            $table->foreignId("expenseCategoryId")->nullable()->references("id")->on("expense_categories");
            $table->foreignId("userId")->nullable()->references("id")->on("users");
            $table->string("title");
            $table->string("note", 500);
            $table->date("dateOfExpense");
            $table->double("amount");
            $table->string("transactionId");
            $table->enum("status", ["0", "1"])->default("1")->comment("1 Active 0 inActive");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
