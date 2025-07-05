<?php

namespace App\Console\Commands;

use App\Models\Analytics;
use App\Models\Element;
use App\Models\ElementType;
use App\Models\Project;
use Illuminate\Console\Command;
use Str;

class DummyAnalysisEvent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:analysis-event {project_id} {type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $projectId = $this->argument('project_id');
        $project = Project::find($projectId);
        if(!$project){
            $this->error('Project not found');
            return;
        }

        $type = $this->argument('type');
        if(!$type){
            $this->error('Type is required');
            return;
        }

        $count = 2000;

        $startDate = now()->startOfMonth();
        $endDate = now();

        $storeElementsType = ElementType::where('is_store', true)->pluck('id');

        $pluckedElementIds = Element::where('project_id', $projectId)
        ->whereIn('element_type_id', $storeElementsType)->pluck('id');
        
        $insert = [];
        for($i = 0; $i < $count; $i++){
            $randomDate = fake()->dateTimeBetween($startDate, $endDate);
            $ipAddress = fake()->ipv4;
            $userAgent = fake()->userAgent;
            $insert[] = [
                'id' => Str::ulid(),
                'project_id' => $projectId,
                'element_id' => $pluckedElementIds->random(),
                'event_type' => $type,
                'event_data' => json_encode([
                    'uri' => fake()->url,
                    'ip_address' => $ipAddress,
                    'user_agent' => $userAgent,
                ]),
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'created_at' => $randomDate,
                'updated_at' => $randomDate,
            ];
        }

        $this->info('Will insert ' . count($insert) . ' data');
        if($this->confirm('Do you want to insert?')){
            Analytics::insert($insert);
            $this->info('Data inserted successfully');
        }
    }
}
