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
            $table->boolean('is_walkable')->default(false); // Whether this element type can be walked on (for pathfinding)
            $table->boolean('is_store')->default(false); // Whether this element type represents a store
            $table->boolean('is_floor_transition')->default(false); // Whether this element type represents an elevator
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
