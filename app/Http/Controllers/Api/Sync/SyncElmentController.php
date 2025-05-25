<?php

namespace App\Http\Controllers\Api\Sync;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sync\SyncElementsRequest;
use App\Http\Requests\SyncMapRequest;
use App\Models\BuildingFootprint;
use App\Services\Sync\SyncElementService;
use App\Services\Sync\SyncMapService;

class SyncElmentController extends Controller
{
    public function syncElements(SyncElementsRequest $request)
    {
        $syncedElements = (new SyncElementService)->syncElements($request->elements);

        return responseJson(['data' => $syncedElements]);
    }

    public function syncMap(SyncMapRequest $request)
    {
        $buildingFootprint = BuildingFootprint::where('id', $request->map_setting['id'])->firstOrFail();
        $data = $request->map_setting;
        $syncedMap = (new SyncMapService)->syncMap($buildingFootprint, $data);

        return responseJson(['data' => $syncedMap]);
    }
}
