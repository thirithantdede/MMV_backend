<?php

use App\Models\Element;
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
        Schema::create('shop_information', function (Blueprint $table) {
            $table->id();
            $table->string('brand_name')->nullable();
            $table->text('detailed_description')->nullable();
            $table->string('logo_url')->nullable();
            $table->string('category')->nullable();
            $table->string('subcategory')->nullable();
            $table->json('business_hours')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->json('social_media')->nullable(); 
            $table->foreignIdFor(Element::class)->constrained()->onDelete('cascade');
            $table->timestamps();

            Schema::create('events', function (Blueprint $table) {
                $table->id();
                $table->foreignId(Element::class)->nullable()->constrained(); // Optional link to a specific location
                $table->string('title');
                $table->text('description')->nullable();
                $table->timestamp('start_date');
                $table->timestamp('end_date');
                $table->string('image_url')->nullable();
                $table->boolean('is_featured')->default(false);
                $table->timestamps();
            });

            Schema::create('promotions', function (Blueprint $table) {
                $table->id();
                $table->foreignIdFor(Element::class)->constrained()->onDelete('cascade'); // Link to the store
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

            Schema::create('floor_connections', function (Blueprint $table) {
                $table->id();
                $table->foreignIdFor(Element::class)->constrained()->onDelete('cascade'); // The element that connects floors
                $table->foreignIdFor(Floor::class,'from_floor_id')->constrained('floors')->onDelete('cascade');
                $table->foreignIdFor(Floor::class,'to_floor_id')->constrained('floors')->onDelete('cascade');
                $table->enum('connection_type', ['elevator', 'stairs'])->default('elevator');
                $table->boolean('is_bidirectional')->default(true); // Whether you can go both up and down
                $table->timestamps();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_information');
    }
};
