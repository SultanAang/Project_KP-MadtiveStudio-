<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create("projects", function (Blueprint $table) {
            $table->id();
            // Client ID harus UNIQUE (1 Client = 1 Project)
            // Kita paksa merujuk ke tabel 'users'
            $table->foreignId("client_id")->constrained("users")->cascadeOnDelete();
            $table->string("name"); // Nama Project
            $table->text("description")->nullable();
            $table->date("deadline")->nullable();
            $table->enum("status", ["pending", "ongoing", "finished"])->default("pending");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists("projects");
    }
};
