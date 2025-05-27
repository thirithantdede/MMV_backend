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
        // Users table
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['admin', 'editor', 'viewer'])->default('viewer');
            $table->rememberToken();
            $table->timestamps();
        });

        // Malls table
        Schema::create('malls', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->string('email')->nullable();
            $table->jsonb('business_hours')->nullable();
            $table->jsonb('building_footprint')->nullable(); // Stores the polygon points of the building outline
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });

        // Element Types table
        Schema::create('element_types', function (Blueprint $table) {
            $table->id();
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

        // Floors table
        Schema::create('floors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mall_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->integer('level'); // Floor number (can be negative for basement levels)
            $table->integer('grid_size')->default(20); // Size of the grid cells in pixels
            $table->integer('width')->default(1000); // Width of the floor in grid cells
            $table->integer('height')->default(1000); // Height of the floor in grid cells
            $table->jsonb('walking_paths')->nullable(); // Stores the walking paths for pathfinding
            $table->timestamps();
            $table->unique(['mall_id', 'level']);
        });

        // Elements table
        Schema::create('elements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('floor_id')->constrained()->onDelete('cascade');
            $table->foreignId('element_type_id')->constrained();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('x'); // X position on the grid
            $table->integer('y'); // Y position on the grid
            $table->integer('width')->default(1); // Width in grid cells
            $table->integer('height')->default(1); // Height in grid cells
            $table->string('color')->nullable(); // Custom color override
            $table->string('icon')->nullable(); // Custom icon override
            $table->jsonb('properties')->nullable(); // Additional properties as JSON
            $table->boolean('is_walkable')->default(false); // Whether this specific element can be walked on
            $table->boolean('is_highlighted')->default(false); // Whether this element is highlighted
            $table->timestamps();
        });

        // Shop Information table
        Schema::create('shop_information', function (Blueprint $table) {
            $table->id();
            $table->foreignId('element_id')->constrained()->onDelete('cascade');
            $table->string('brand_name')->nullable();
            $table->text('detailed_description')->nullable();
            $table->string('logo_url')->nullable();
            $table->string('category')->nullable();
            $table->string('subcategory')->nullable();
            $table->jsonb('business_hours')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->jsonb('social_media')->nullable(); // JSON with social media links
            $table->timestamps();
        });

        // Events table
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mall_id')->constrained()->onDelete('cascade');
            $table->foreignId('element_id')->nullable()->constrained(); // Optional link to a specific location
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->string('image_url')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });

        // Promotions table
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('element_id')->constrained()->onDelete('cascade'); // Link to the store
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->string('image_url')->nullable();
            $table->string('discount_type')->nullable(); // percentage, fixed amount, etc.
            $table->decimal('discount_value', 10, 2)->nullable();
            $table->string('promo_code')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });

        // Floor Connections table (for elevators, escalators, stairs)
        Schema::create('floor_connections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('element_id')->constrained()->onDelete('cascade'); // The element that connects floors
            $table->foreignId('from_floor_id')->constrained('floors')->onDelete('cascade');
            $table->foreignId('to_floor_id')->constrained('floors')->onDelete('cascade');
            $table->enum('connection_type', ['elevator', 'escalator', 'stairs'])->default('elevator');
            $table->boolean('is_bidirectional')->default(true); // Whether you can go both up and down
            $table->timestamps();
        });

        // Map Versions table (for tracking changes and publishing)
        Schema::create('map_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mall_id')->constrained()->onDelete('cascade');
            $table->string('version_name');
            $table->text('change_notes')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        // Analytics table (for tracking user interactions with the map)
        Schema::create('analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mall_id')->constrained()->onDelete('cascade');
            $table->foreignId('element_id')->nullable()->constrained(); // Optional link to a specific element
            $table->string('event_type'); // search, route, click, view, etc.
            $table->jsonb('event_data')->nullable(); // Additional data about the event
            $table->string('user_agent')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });

        // User Permissions table
        Schema::create('user_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('mall_id')->constrained()->onDelete('cascade');
            $table->enum('permission', ['owner', 'editor', 'viewer'])->default('viewer');
            $table->timestamps();
            $table->unique(['user_id', 'mall_id']);
        });

        // Export Templates table
        Schema::create('export_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->jsonb('settings'); // Export settings (format, quality, included elements, etc.)
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        // Export History table
        Schema::create('export_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('mall_id')->constrained()->onDelete('cascade');
            $table->foreignId('export_template_id')->nullable()->constrained();
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_type'); // png, jpg, pdf, svg, json, etc.
            $table->integer('file_size')->nullable(); // Size in bytes
            $table->jsonb('export_settings'); // The settings used for this export
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop tables in reverse order to avoid foreign key constraints
        Schema::dropIfExists('export_history');
        Schema::dropIfExists('export_templates');
        Schema::dropIfExists('user_permissions');
        Schema::dropIfExists('analytics');
        Schema::dropIfExists('map_versions');
        Schema::dropIfExists('floor_connections');
        Schema::dropIfExists('promotions');
        Schema::dropIfExists('events');
        Schema::dropIfExists('shop_information');
        Schema::dropIfExists('elements');
        Schema::dropIfExists('floors');
        Schema::dropIfExists('element_types');
        Schema::dropIfExists('malls');
        Schema::dropIfExists('users');
    }
};
