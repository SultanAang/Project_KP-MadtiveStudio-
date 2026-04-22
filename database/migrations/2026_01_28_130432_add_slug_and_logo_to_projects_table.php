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
            // Menambahkan kolom slug setelah nama (harus unik untuk URL)
            $table->string("slug")->nullable()->unique()->after("name");

            // Menambahkan kolom logo setelah slug
            $table->string("logo")->nullable()->after("slug");
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
