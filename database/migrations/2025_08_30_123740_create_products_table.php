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
        Schema::create('products', function (Blueprint $table) {
            //snakecase for laravel convention 
            $table->id(); 
            $table->string('name'); //Name
            $table->string('category'); //Category
            $table->text('description')->nullable(); //Description
            $table->dateTime('date_time'); //Date and Time
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
