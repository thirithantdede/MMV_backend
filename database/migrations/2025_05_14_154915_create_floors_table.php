<?php

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
        Schema::create('floors', function (Blueprint $table) {
            $table->ulid();
            $table->string('name');
            $table->integer('level'); // Floor number (can be negative for basement levels)
            $table->integer('grid_size')->default(20); // Size of the grid cells in pixels
            $table->json('walking_paths')->nullable(); // Stores the walking paths for pathfinding
            $table->foreignIdFor(Project::class);
            $table->timestamps();
            $table->unique(['project_id', 'level']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('floors');
    }
};
