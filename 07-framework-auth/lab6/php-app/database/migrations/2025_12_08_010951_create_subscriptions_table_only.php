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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('service');
            $table->string('topic');
            $table->json('payload')->nullable();
            $table->timestamp('expired_at')->nullable();
            // ВАЖНО: Внешний ключ пока не добавляем!
            $table->unsignedBigInteger('subscriber_id'); // <--- ВРЕМЕННЫЙ СТОЛБЕЦ
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
