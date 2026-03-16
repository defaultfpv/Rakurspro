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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->text('type')->nullable();
            $table->text('name')->nullable();
            $table->text('slug')->nullable();
            $table->text('title')->nullable();
            $table->text('description')->nullable();
            $table->text('h1')->nullable();
            $table->text('banner_text')->nullable();
            $table->text('html1')->nullable();
            $table->text('html2')->nullable();
            $table->text('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
