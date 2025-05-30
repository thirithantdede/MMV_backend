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
        Schema::create('building_footprints', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignIdFor(Project::class);
            $table->unsignedInteger('width')->default(3000);
            $table->unsignedInteger('height')->default(2000);
            $table->unsignedInteger('building_x')->default(700);
            $table->unsignedInteger('building_y')->default(400);
            $table->unsignedInteger('building_width')->default(1600);
            $table->unsignedInteger('building_height')->default(1200);
            $table->boolean('restricted')->default(true);
            $table->boolean('show_grid')->default(true);
            $table->boolean('show_opening_hours')->default(true);
            $table->unsignedSmallInteger('grid_size')->default(20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('building_footprints');
    }
};
