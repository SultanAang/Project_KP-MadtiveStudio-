<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create("releases", function (Blueprint $table) {
            $table->id();

            // 1. UNTUK SIDEBAR (Pengelompokan)
            // Contoh isi: "Version 2.x" atau "Version 1.x"
            $table->string("group")->default("Version 2.x");

            // 2. UNTUK JUDUL VERSI
            // Contoh isi: "2.5.0"
            $table->string("version");

            // 3. JUDUL BESAR DI TENGAH
            // Contoh isi: "Version 2.5 Major Update & New Features"
            $table->string("title");

            // 4. TEKS BERGELOMBANG (Squiggly Text)
            // Kita pakai 'text' agar muat paragraf panjang & format bold/italic
            $table->text("intro_text")->nullable();

            // 5. FITUR-FITUR (Key Features List)
            // INI PENTING: Kita pakai tipe 'json'.
            // Kenapa? Agar kita bisa menyimpan daftar fitur (Icon + Judul + Deskripsi)
            // dalam satu kolom saja, tidak peduli jumlahnya ada 3, 5, atau 10.
            $table->json("features")->nullable();

            // 6. TANGGAL RILIS (Metadata)
            // Agar bisa diurutkan dari yang terbaru
            $table->date("published_at")->default(now());

            // 7. STATUS TAMPIL
            // Agar Anda bisa bikin draft dulu sebelum dirilis ke publik
            $table->boolean("is_visible")->default(true);

            $table->string("is_approve")->default("draft")->index();

            // Opsional: Catatan dari reviewer jika ditolak
            $table->text("rejection_note")->nullable();
            $table->foreignId("created_by")->nullable()->constrained("users")->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists("releases");
    }
};
