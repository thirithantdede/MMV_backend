<?php

namespace App\Http\Controllers\Api\UserData;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Services\Element\BaseElement;
use App\Services\Element\StoreElement;
use Illuminate\Http\Request;

class UserDataProviderController extends Controller
{
    public function getPromotionElements(Request $request){
        $project = $request->project_id;
        $project = Project::find($project);
        $floorIds = $project->floors->pluck('id')->toArray();

        $elements = (new StoreElement)->getPromotionElements($floorIds);

        return responseJson(['elements' => $elements]);
    }

    public function getEventElements(Request $request){
        $project = $request->project_id;
        $project = Project::find($project);
        $floorIds = $project->floors->pluck('id')->toArray();
        $elements = (new StoreElement)->getEventElements($floorIds);
        return responseJson(['elements' => $elements]);
    }

    public function searchElements(Request $request){
        $project = $request->project_id;
        $project = Project::find($project);
        $floorIds = $project->floors->pluck('id')->toArray();
        $elements = (new BaseElement)->searchElements( $request->search,$project);
        return responseJson(['elements' => $elements]);
    }

}
