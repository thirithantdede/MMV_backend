<?php

namespace App\Http\Controllers\Api\Sync;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sync\SyncElementsRequest;
use App\Services\Sync\SyncElementService;

class SyncElmentController extends Controller
{
    public function syncElements(SyncElementsRequest $request)
    {
        $syncedElements = (new SyncElementService)->syncElements($request->elements);

        return responseJson(['data' => $syncedElements]);
    }
}
