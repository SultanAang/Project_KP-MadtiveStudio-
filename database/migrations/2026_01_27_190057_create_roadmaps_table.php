<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create("roadmaps", function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->string("status"); // planned, in_progress, done
            $table->date("eta")->nullable();
            $table->text("description")->nullable();
            $table->string('is_approve')->default('draft')->index();
            $table->text('rejection_note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists("roadmaps");
    }
};
