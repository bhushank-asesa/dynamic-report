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
        Schema::create('main_locations', function (Blueprint $table) {
            $table->id();
            $table->string("mainLocationName");
            $table->enum("status", ["0", "1"])->default("1")->comment("1 Active 0 inActive");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('main_locations');
    }
};
