<?php

use App\Models\User;
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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('photo')->nullable();
            $table->string('address')->nullable();
            $table->string('website')->nullable();
            $table->boolean('is_published')->default(false);
            $table->string('uri')->nullable()->unique();
            $table->boolean('is_public')->default(true);
            $table->string('current_version')->default("1.0.0");
            $table->timestamp('published_at')->nullable();
            $table->foreignIdFor(User::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
