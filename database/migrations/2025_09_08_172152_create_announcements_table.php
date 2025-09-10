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
    Schema::create('announcement', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('content');
        $table->string('category')->nullable();
        $table->dateTime('publishDate');
        $table->unsignedBigInteger('courseID')->nullable();
        $table->foreign('courseID')->references('id')->on('course')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::dropIfExists('announcement');
}

};
