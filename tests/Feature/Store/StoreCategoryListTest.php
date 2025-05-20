<?php

use Database\Seeders\StoreCategorySeeder;
use Tests\Support\UserAuthenticated;

uses()->group('store', 'storecategory');
uses(UserAuthenticated::class);
beforeEach(function () {
    $this->seed(StoreCategorySeeder::class);
    $this->setupUser();
});

it('has store/storecategorylist page', function () {
    $this->actingAs($this->user);
    $response = $this->get(route('store-category.list'));
    $response->assertStatus(200);
});

it('Store Category are retrieved from Redis', function () {
    $this->actingAs($this->user);
    $response = $this->get(route('store-category.list'));
    $response->assertStatus(200);
    // find in Cache::get
    $key = 'store-category-list';

    $this->assertTrue(\Illuminate\Support\Facades\Cache::has($key));
});
