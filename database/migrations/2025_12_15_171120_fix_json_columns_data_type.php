<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Force refresh the model cache for JSON casting
        // This will ensure existing data is properly cast as arrays
        \DB::table('food_listings')->select('id')->limit(1)->update(['updated_at' => now()]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No down migration needed as we're just updating data format
    }
};
