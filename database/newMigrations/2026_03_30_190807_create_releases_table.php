<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('releases', function (Blueprint $table) {
            $table->increments('id');
            $table->string('group')->default('Version 2.x');
            $table->string('version');
            $table->string('title');
            $table->text('intro_text')->nullable();
            $table->text('features')->nullable();
            $table->date('published_at')->default('2026-02-01 09:19:49');
            $table->boolean('is_visible')->default(true);
            $table->string('is_approve')->default('draft');
            $table->text('rejection_note')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->integer('project_id');
            $table->integer('created_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('releases');
    }
};
