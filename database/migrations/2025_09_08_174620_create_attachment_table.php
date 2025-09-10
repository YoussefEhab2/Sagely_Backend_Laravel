<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('attachment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('announcementID')
                  ->constrained('announcement')
                  ->onDelete('cascade'); // delete attachments if announcement is deleted
            $table->string('file_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attachment');
    }
};
