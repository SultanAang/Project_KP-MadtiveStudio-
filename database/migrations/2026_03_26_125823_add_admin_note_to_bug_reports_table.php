<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table("bug_reports", function (Blueprint $table) {
            // Menambahkan kolom admin_note setelah kolom status
            $table->text("admin_note")->nullable()->after("status");
        });
    }

    public function down(): void {
        Schema::table("bug_reports", function (Blueprint $table) {
            // Menghapus kolom jika di-rollback
            $table->dropColumn("admin_note");
        });
    }
};
