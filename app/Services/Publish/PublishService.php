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
        $project = Project::where('uri', $request->uri)->where('is_public',true)->firstOrFail();
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

    public function publish(Request $request)
    {
        $project = auth()->user()->project;

        $project->name = $request->name;
        $project->description = $request->description;
        $project->uri = $request->uri;
        $project->is_public = $request->is_public;

        // Increment patch version
        $version = $project->current_version ?? '1.0.0';
        $parts = explode('.', $version);

        if (count($parts) === 3) {
            $parts[2] = (int)$parts[2] + 1; // increment patch
            $project->current_version = implode('.', $parts);
        } else {
            $project->current_version = '1.0.0'; // fallback if version format is incorrect
        }

        $project->published_at = now();
        $project->save();

        return $project;
    }

}
