<?php

use App\Models\Element;
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
        Schema::create('analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Project::class);
            $table->foreignIdFor(Element::class)->nullable(); // Optional link to a specific element
            $table->string('event_type'); // search, route, click, view, etc.
            $table->jsonb('event_data')->nullable(); // Additional data about the event
            $table->string('user_agent')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });

        Schema::create('project_views', function (Blueprint $table) {
            $table->foreignIdFor(Project::class)->nullable(); // Optional link to a specific element
            $table->string("ip_address")->nullable();
            $table->string("user_agent")->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analytics');
        Schema::dropIfExists('project_views');
    }
};
