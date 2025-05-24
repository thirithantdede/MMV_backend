<?php

use App\Models\ElementType;
use App\Models\Floor;
use App\Models\Project;
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
            $table->integer('opacity')->default(0); // Opacity in degrees
            $table->string('color')->nullable(); // Custom color override
            $table->string('border_style')->default('solid'); // Custom color override
            $table->string('icon')->nullable(); // Custom icon override
            $table->string('notes')->nullable(); // Custom icon override
            $table->boolean('is_closed')->default(false); // Custom icon override
            $table->boolean('walkable')->default(false); // Custom icon override
            $table->json('properties')->nullable(); // Additional properties as JSON
            $table->json('border_radius')->default('{"topLeft": 0, "topRight": 0, "bottomRight": 0, "bottomLeft": 0}');
            $table->foreignIdFor(Floor::class);
            $table->foreignIdFor(ElementType::class);
            $table->foreignIdFor(Project::class);
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
