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
        Schema::table('food_listings', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->nullable()->after('pickup_location');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->string('pickup_address')->nullable()->after('longitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('food_listings', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'pickup_address']);
        });
    }
};
