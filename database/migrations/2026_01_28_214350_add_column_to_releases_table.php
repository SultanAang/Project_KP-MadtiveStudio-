<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        // Schema::table("releases", function (Blueprint $table) {
        //     //
        //     $table->foreignId("project_id")->constrained("projects")->cascadeOnDelete();

        //     // 2. Data Release Note (Sesuai Form Filament)
        //     // $table->string("version"); // Contoh: v1.0
        //     $table->text("changelog"); // Contoh: Perbaikan bug login
        // });
        Schema::table("faqs", function (Blueprint $table) {
            //
            $table->foreignId("project_id")->constrained("projects")->cascadeOnDelete();
        });
        Schema::table("knowledge_bases", function (Blueprint $table) {
            //
            $table->foreignId("project_id")->constrained("projects")->cascadeOnDelete();
        });
        Schema::table("roadmaps", function (Blueprint $table) {
            //
            $table->foreignId("project_id")->constrained("projects")->cascadeOnDelete();
        });
        Schema::table("releases", function (Blueprint $table) {
            //
            $table->foreignId("project_id")->constrained("projects")->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table("releases", function (Blueprint $table) {
            //
        });
    }
};
