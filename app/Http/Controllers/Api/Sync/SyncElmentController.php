<?php

namespace App\Http\Controllers\Api\Sync;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sync\SyncElementsRequest;
use App\Http\Requests\SyncMapRequest;
use App\Models\BuildingFootprint;
use App\Models\Element;
use App\Services\Sync\SyncElementService;
use App\Services\Sync\SyncMapService;
use Illuminate\Http\Request;

class SyncElmentController extends Controller
{
    public function syncElements(SyncElementsRequest $request)
    {
        $syncedElements = (new SyncElementService)->syncElements($request->elements);

        return responseJson(['data' => $syncedElements]);
    }

    public function syncMap(SyncMapRequest $request)
    {
        $buildingFootprint = auth()->user()->project->buildingFootprint;
        $data = $request->map_setting;
        $syncedMap = (new SyncMapService)->syncMap($buildingFootprint, $data);

        return responseJson(['data' => $syncedMap]);
    }

    public function deleteElement(Request $request){
        $element = Element::findOrFail($request->id);
        $element->delete();
        return true;
    }
}
