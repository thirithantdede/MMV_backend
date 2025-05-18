<?php

use App\Models\ElementType;
use App\Models\Floor;
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
        Schema::create('elements', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('x'); // X position on the grid
            $table->integer('y'); // Y position on the grid
            $table->integer('width')->default(1); // Width in grid cells
            $table->integer('height')->default(1); // Height in grid cells
            $table->integer('rotation')->default(0); // Rotation in degrees
            $table->string('color')->nullable(); // Custom color override
            $table->string('icon')->nullable(); // Custom icon override
            $table->json('properties')->nullable(); // Additional properties as JSON
            $table->foreignIdFor(Floor::class);
            $table->foreignIdFor(ElementType::class);
            $table->timestamps();

            // index floor_id
            $table->index('floor_id');
            $table->index('element_type_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elements');
    }
};
