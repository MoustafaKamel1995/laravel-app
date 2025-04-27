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
        Schema::create('blocked_countries', function (Blueprint $table) {
            $table->id();
            $table->string('country_code', 2)->unique()->comment('ISO 3166-1 alpha-2 country code');
            $table->string('country_name');
            $table->boolean('is_blocked')->default(true);
            $table->text('block_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blocked_countries');
    }
};
