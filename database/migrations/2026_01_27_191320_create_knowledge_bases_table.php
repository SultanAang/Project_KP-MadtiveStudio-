<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create("knowledge_bases", function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->string("slug")->unique(); // Untuk URL artikel
            $table->string("category")->nullable();
            $table->longText("content")->nullable();
            $table->timestamps();
            $table->string("is_approve")->default("draft")->index();

            // Opsional: Catatan dari reviewer jika ditolak
            $table->text("rejection_note")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists("knowledge_bases");
    }
};
