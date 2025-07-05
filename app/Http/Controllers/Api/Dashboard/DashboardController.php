<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Services\Dashboard\DashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function statistics(): JsonResponse
    {
        $user = auth()->user();

        $dateType = request()->get("date_type","day");

        $project = Project::where("user_id",$user->id)->first();
        
        $dashboard = new DashboardService($project,$dateType);

        $totalVisitors = $dashboard->getTotalVisitors();
        $totalEvents = $dashboard->getTotalEvents();
        $routeSearch = $dashboard->getRouteSearch();
        $activeStores = $dashboard->getActiveStores();
        
        return response()->json([
            'status' => 'success',
            'data' => [
                'totalVisitors' => $totalVisitors,
                'totalEvents' => $totalEvents,
                'totalRouteSearches' => $routeSearch,
                'totalActiveStores' => $activeStores,
            ],
        ]);
    }

    public function popularStores(): JsonResponse
    {
        $user = auth()->user();
        $dateType = request()->get("date_type","day");
        $project = Project::where("user_id",$user->id)->first();
        $dashboard = new DashboardService($project,$dateType);
        $popularStores = $dashboard->getPopularStores();

        return response()->json([
            'status' => 'success',
            'data' => $popularStores,
        ]);
    }

    public function visitorTracks(): JsonResponse
    {
        $user = auth()->user();
        $project = Project::where("user_id",$user->id)->first();
        $dateType = request()->get("date_type","hour");
        $dashboard = new DashboardService($project,$dateType);
        $visitorTracks = $dashboard->visitorTracks();

        return response()->json([
            'status' => 'success',
            'data' => $visitorTracks,
        ]);
    }

    public function getTableAnaylytic(): JsonResponse
    {
        $user = auth()->user();
        $project = Project::where("user_id",$user->id)->first();
        $dateType = request()->get("date_type","hour");
        $dashboard = new DashboardService($project,$dateType);

        $tableAnalytic = $dashboard->getTableAnalytic();

        return response()->json([
            'status' => 'success',
            'data' => $tableAnalytic,
        ]);
    }

}
