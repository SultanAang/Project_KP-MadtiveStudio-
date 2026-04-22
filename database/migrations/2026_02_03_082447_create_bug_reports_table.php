<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create("bug_reports", function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained()->onDelete("cascade"); // Pelapor
            $table->string("title");
            $table->text("description");
            $table->enum("priority", ["low", "medium", "high", "critical"])->default("medium");
            $table
                ->enum("status", ["pending", "process", "resolved", "rejected"])
                ->default("pending");
            $table->string("screenshot_path")->nullable(); // Path gambar
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists("bug_reports");
    }
};
