<?php

namespace App\Http\Controllers\Api\Store;

use App\Http\Controllers\Controller;
use App\Services\Store\StoreCategoryService;

class StoreCategoryController extends Controller
{
    public function index()
    {
        $stores = (new StoreCategoryService)->getList();

        return responseJson(['data' => $stores], 200);
    }
}
