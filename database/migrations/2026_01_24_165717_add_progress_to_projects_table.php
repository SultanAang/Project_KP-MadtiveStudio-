<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table("projects", function (Blueprint $table) {
            // Kolom integer untuk menyimpan % (0 sampai 100)
            $table->integer("progress")->default(0)->after("status");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table("projects", function (Blueprint $table) {
            //
        });
    }
};
