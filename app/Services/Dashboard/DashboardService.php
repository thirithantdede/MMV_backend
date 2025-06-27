<?php

namespace App\Services\Dashboard;

use App\Models\Analytics;
use App\Models\Element;
use App\Models\ElementType;
use App\Models\Project;
use App\Models\ProjectView;
use App\Services\Element\BaseElement;
use DB;
use Log;

class DashboardService
{

    protected string $startDate;
    protected string $endDate;
    
    protected $type = "day";

    public function __construct(public Project $project,public string $dateType) {
        $this->setTimePeriod($dateType);
    }

    public function setTimePeriod(string $type = "day"){
        $this->type = $type;
        switch($type){
            case "hour":
                $this->startDate = now()->subMinutes(60);
                $this->endDate = now();
            break;
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
            default:
                $this->startDate = now()->startOfDay();
                $this->endDate = now()->endOfDay();
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


    // { label: "label", data: count } at leat 12 items if type was hours we will take 5 mintues 12 times and if type was day we will take 2 hour 12 times 
    // and if type was week take 0.5 day 12 times and if type was month take group by each day

    public function visitorTracks()
    {
        $data = [];
        
        switch($this->type) {
            case "hour":
                // 5-minute intervals for 12 times (60 minutes total)
                for ($i = 0; $i < 12; $i++) {
                    $startTime = now()->subMinutes(60)->addMinutes($i * 5);
                    $endTime = $startTime->copy()->addMinutes(5);
                    
                    $count = ProjectView::where('project_id', $this->project->id)
                        ->whereBetween('created_at', [$startTime, $endTime])
                        ->count();
                    
                    $data[] = [
                        'label' => $startTime->format('H:i'),
                        'data' => $count
                    ];
                }
                break;
                
            case "day":
                // 2-hour intervals for 12 times (24 hours total)
                for ($i = 0; $i < 12; $i++) {
                    $startTime = now()->startOfDay()->addHours($i * 2);
                    $endTime = $startTime->copy()->addHours(2);
                    
                    $count = ProjectView::where('project_id', $this->project->id)
                        ->whereBetween('created_at', [$startTime, $endTime])
                        ->count();
                    
                    $data[] = [
                        'label' => $startTime->format('H:i'),
                        'data' => $count
                    ];
                }
                break;
                
            case "week":
                // 0.5 day intervals for 12 times (6 days total)
                for ($i = 0; $i < 12; $i++) {
                    $startTime = now()->startOfWeek()->addDays($i * 0.5);
                    $endTime = $startTime->copy()->addDays(0.5);
                    
                    $count = ProjectView::where('project_id', $this->project->id)
                        ->whereBetween('created_at', [$startTime, $endTime])
                        ->count();
                    
                    $data[] = [
                        'label' => $startTime->format('D H:i'),
                        'data' => $count
                    ];
                }
                break;
                
            case "month":
                // Group by each day of the month
                $daysInMonth = now()->daysInMonth;
                $startOfMonth = now()->startOfMonth();
                
                for ($i = 0; $i < $daysInMonth; $i++) {
                    $startTime = $startOfMonth->copy()->addDays($i);
                    $endTime = $startTime->copy()->endOfDay();
                    
                    $count = ProjectView::where('project_id', $this->project->id)
                        ->whereBetween('created_at', [$startTime, $endTime])
                        ->count();
                    
                    $data[] = [
                        'label' => $startTime->format('M d'),
                        'data' => $count
                    ];
                }
                break;
                
            default:
                // Default to day format
                for ($i = 0; $i < 12; $i++) {
                    $startTime = now()->startOfDay()->addHours($i * 2);
                    $endTime = $startTime->copy()->addHours(2);
                    
                    $count = ProjectView::where('project_id', $this->project->id)
                        ->whereBetween('created_at', [$startTime, $endTime])
                        ->count();
                    
                    $data[] = [
                        'label' => $startTime->format('H:i'),
                        'data' => $count
                    ];
                }
                break;
        }

        return $data;
    }

    public function getPopularStores()
    {
        $topStores = Analytics::select('element_id',DB::raw('count(element_id) as total'))
        ->where('project_id','=', $this->project->id)
        ->whereBetween('created_at', [$this->startDate, $this->endDate])
        ->groupBy('element_id')
        ->orderBy('total','desc')
        ->limit(10)
        ->get();

        $stores = Element::select('id','name','element_type_id','floor_id')
        ->whereIn('id',$topStores->pluck('element_id'))
        ->withCount('analytics')
        ->orderBy('analytics_count','desc')
        ->get();

        Log::info($topStores->count());

        $maxTotal = $topStores->max('total') ?: 0; // Fallback to 0 if no data
        $maxAverage = $maxTotal > 0 ? ceil($maxTotal * 1.1) : 10; // 10% above max, min 10 if no data
        $maxAverage = max($maxAverage, $stores->max('analytics_count')); // Ensure it’s at least max analytics_count
    
        // Map maxAverage to stores
        $stores = $stores->map(function ($store) use ($maxAverage) {
            $store->target = $maxAverage;
            return $store;
        })->values(); // Reindex array to match your JSON structure
    
        return $stores;
    }
}
