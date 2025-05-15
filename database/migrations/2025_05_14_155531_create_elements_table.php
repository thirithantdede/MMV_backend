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
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('x'); // X position on the grid
            $table->integer('y'); // Y position on the grid
            $table->integer('width')->default(1); // Width in grid cells
            $table->integer('height')->default(1); // Height in grid cells
            $table->string('color')->nullable(); // Custom color override
            $table->string('icon')->nullable(); // Custom icon override
            $table->json('properties')->nullable(); // Additional properties as JSON
            $table->boolean('is_walkable')->default(false); // Whether this specific element can be walked on
            $table->boolean('is_highlighted')->default(false); // Whether this element is highlighted
            $table->foreignIdFor(Floor::class);
            $table->foreignIdFor(ElementType::class);
            $table->timestamps();
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
