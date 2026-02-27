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
        Schema::create('tables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();

            $table->unsignedInteger('number'); // mesa 1,2,3...
            $table->string('qr_token')->unique(); // token pro QR
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->unique(['restaurant_id', 'number']); // não repetir mesa por restaurante
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tables');
    }
};
