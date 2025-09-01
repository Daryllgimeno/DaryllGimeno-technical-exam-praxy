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
 
{
    Schema::table('products', function (Blueprint $table) {
        if (!Schema::hasColumn('products', 'images')) {
            $table->json('images')->nullable()->after('date_time');
        }
    });
}
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
           $table->dropColumn('images');
        });
    }
};
