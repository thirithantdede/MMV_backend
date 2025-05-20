<?php

namespace App\Services\Store;

use App\Models\StoreCategory;
use App\Traits\CacheResponse;

class StoreCategoryService
{
    use CacheResponse;

    public function __construct() {}

    public function getList()
    {
        return $this->cacheResponse('store-category-list', 60, function () {
            return StoreCategory::all();
        });
    }
}
