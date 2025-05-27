<?php

namespace App\Services\Publish;

use App\Models\Project;
use App\Services\BuildingFootPrint\BuildingFootPrintService;
use App\Services\Element\ElementService;
use App\Services\Floor\FloorService;
use Illuminate\Http\Request;

class PublishService
{
    public function getData(Request $request){
        $project = Project::where('uri', $request->uri)->first();
        if($project->is_public == false){
            return responseJson([
                'message' => 'Project is not public',
            ], 401);
        }
        $floors = (new FloorService)->getFloors($project);
        $building_footprint = (new BuildingFootPrintService)->getBuildingFootPrint($project);
        $elements = (new ElementService)->getElements($project);

        return [
            'project' => $project,
            'floors' => $floors,
            'building_footprint' => $building_footprint,
            'elements' => $elements,
        ];
    }
}
