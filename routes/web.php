<?php

use App\Models\Project;
use App\Services\Dashboard\DashboardService;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/test', action: function () {
    $project = Project::where('id', 1)->first();
    $dashboard = new DashboardService($project, "hour");
});
