<?php

namespace App\Services\Dashboard;

use App\Models\Project;
use App\Models\ProjectView;

class DashboardService
{

    public function __construct(public Project $project, public string $startDate, public string $endDate) {}


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
        }
    }

    public function getTotalViews(): int
    {
        return ProjectView::count();
    }
}
