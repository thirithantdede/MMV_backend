<?php

namespace App\Services\Dashboard;

use App\Models\Analytics;
use App\Models\Element;
use App\Models\ElementType;
use App\Models\Project;
use App\Models\ProjectView;
use App\Services\Element\BaseElement;

class DashboardService
{

    protected string $startDate;
    protected string $endDate;

    public function __construct(public Project $project,public string $dateType) {
        $this->setTimePeriod($dateType);
    }

    public function setTimePeriod(string $type = "day"){
        switch($type){
            case "day":
                $this->startDate = now()->startOfDay();
                $this->endDate = now()->endOfDay();
                break;
            case "week":
                $this->startDate = now()->startOfWeek();
                $this->endDate = now()->endOfWeek();
                break;
            case "month":
                $this->startDate = now()->startOfMonth();
                $this->endDate = now()->endOfMonth();
                break;
            case "year":
                $this->startDate = now()->startOfYear();
                $this->endDate = now()->endOfYear();
                break;
        }
    }

    public function getRouteSearch():int
    {
        return Analytics::query()->where('event_type','from-route')
        ->where('project_id','=', $this->project->id)
        ->whereBetween('created_at', [$this->startDate, $this->endDate])
        ->count();
    }

    public function getActiveStores() :int
    {        
        $types = ElementType::where('is_store',true)->first();
        return Element::where('element_type_id', $types->id)
        ->where('project_id','=', $this->project->id)
        ->whereBetween('created_at', [$this->startDate, $this->endDate])
        ->count();
    }

    public function getTotalVisitors()
    {
        return ProjectView::where("project_id","=", $this->project->id)
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->count();
    }
    
    public function getTotalEvents(): int
    {
        $eventElementType = ElementType::where('name','event')->first();
        return Element::where('element_type_id','=', $eventElementType->id)
        ->where('project_id','=', $this->project->id)
        ->whereBetween('created_at', [$this->startDate, $this->endDate])
        ->count();
    }
}
