<?php

use App\Models\Element;
use App\Models\Floor;
use App\Models\StoreCategory;
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
            $table->ulid('id')->primary();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('logo')->nullable();
            $table->boolean('is_foc')->nullable();
            $table->string('website')->nullable();
            $table->jsonb('opening_hours')->nullable();
            $table->jsonb('closed_days')->nullable();
            $table->jsonb('social_media')->nullable();
            $table->jsonb('promotions')->nullable();
            $table->foreignIdFor(Element::class);
            $table->foreignIdFor(StoreCategory::class)->default(1);
            $table->timestamps();
        });

        Schema::create('events', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignIdFor(Element::class)->nullable(); // Optional link to a specific location
            $table->string('title');
            $table->string('company');
            $table->string('hosts');
            $table->text('description')->nullable();
            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->string("start_time")->nullable();
            $table->string("end_time")->nullable();
            $table->boolean("is_foc")->default(false);
            $table->string('image_url')->nullable();
            $table->boolean('is_active')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });

        Schema::create('promotions', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignIdFor(Element::class); // Link to the store
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->string("start_time")->nullable();
            $table->string("end_time")->nullable();
            $table->string('image_url')->nullable();
            $table->string('discount_type')->nullable(); // percentage, fixed amount, etc.
            $table->decimal('discount_value', 10, 2)->nullable();
            $table->string('promo_code')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });

        Schema::create('floor_connections', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignIdFor(Element::class); // The element that connects floors
            $table->foreignIdFor(Floor::class, 'from_floor_id');
            $table->foreignIdFor(Floor::class, 'to_floor_id');
            $table->enum('connection_type', ['elevator', 'stairs'])->default('elevator');
            $table->boolean('is_bidirectional')->default(true); // Whether you can go both up and down
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_information');
        Schema::dropIfExists('events');
        Schema::dropIfExists('promotions');
        Schema::dropIfExists('floor_connections');
    }
};
