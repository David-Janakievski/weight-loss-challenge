<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('checkins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('week_number');
            $table->date('checkin_date');
            $table->decimal('weight', 6, 2);
            $table->string('photo');
            $table->string('note')->nullable();
            $table->boolean('admin_override')->default(false);
            $table->timestamps();

            $table->unique(['user_id', 'week_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checkins');
    }
};
