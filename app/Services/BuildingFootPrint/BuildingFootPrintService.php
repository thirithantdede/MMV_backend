<?php

namespace App\Services\BuildingFootPrint;

use App\Models\Project;

class BuildingFootPrintService
{
    public function getBuildingFootPrint(Project $project)
    {
        return $project->buildingFootPrint;
    }
}
