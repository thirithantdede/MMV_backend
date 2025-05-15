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
        Schema::create('element_types', function (Blueprint $table) {
            $table->ulid();
            $table->string('name');
            $table->string('icon')->nullable();
            $table->string('color')->nullable();
            $table->boolean('is_walkable')->default(false); // Whether this element type can be walked on (for pathfinding)
            $table->boolean('is_store')->default(false); // Whether this element type represents a store
            $table->boolean('is_facility')->default(false); // Whether this element type represents a facility
            $table->boolean('is_entrance')->default(false); // Whether this element type represents an entrance/exit
            $table->boolean('is_elevator')->default(false); // Whether this element type represents an elevator
            $table->boolean('is_escalator')->default(false); // Whether this element type represents an escalator
            $table->boolean('is_stairs')->default(false); // Whether this element type represents stairs
            $table->boolean('is_restroom')->default(false); // Whether this element type represents a restroom
            $table->boolean('is_information')->default(false); // Whether this element type represents an information desk
            $table->boolean('is_food')->default(false); // Whether this element type represents a food court or restaurant
            $table->boolean('is_atm')->default(false); // Whether this element type represents an ATM
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('element_types');
    }
};
