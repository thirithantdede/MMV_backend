<?php

namespace App\Http\Controllers\Api\Loading;

use App\Http\Controllers\Controller;
use App\Services\BuildingFootPrint\BuildingFootPrintService;
use App\Services\Element\ElementService;
use App\Services\Floor\FloorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LoadingController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $project = auth()->user()->project;
        $floors = (new FloorService)->getFloors($project);
        $building_footprint = (new BuildingFootPrintService)->getBuildingFootPrint($project);

        return responseJson([
            'project' => $project,
            'floors' => $floors,
            'building_footprint' => $building_footprint,
            '',
        ]);
    }

    public function projectElements(Request $request): JsonResponse
    {
        $project = auth()->user()->project;
        $elements = (new ElementService)->getElements($project);

        return responseJson([
            'elements' => $elements,
        ]);
    }
}
