<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('save_locs', function (Blueprint $table) {
            $table->uuid('uuid')->default(DB::raw('(UUID())'))->primary();
            $table->foreignId('user_id')->constrained('users');
            $table->decimal('lon', 8, 5);
            $table->decimal('lat', 8, 5);
            
            // $table->decimal('lon', 8, 5)->change();
            // $table->decimal('lat', 8, 5)->change();

            $table->string('region', 50);
            $table->string('province', 50);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('save_location');
    }
};
