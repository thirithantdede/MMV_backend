<?php

namespace App\Console\Commands;

use App\Models\Project;
use App\Models\ProjectView;
use Illuminate\Console\Command;

class DummyVisitorSeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
        protected $signature = 'seed:visitor {project_id} {count}';

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

        $count = $this->argument('count');
        if(!$count){
            $this->error('Count is required');
            return;
        }
        // real data like ip address and user agent
        $startDate = now()->startOfMonth();
        $endDate = now();
        // generate random date between start of this month and and now
        $insert = [];
        for($i = 0; $i < $count; $i++){
            $randomDate = fake()->dateTimeBetween($startDate, $endDate);
            $ipAddress = fake()->ipv4;
            $userAgent = fake()->userAgent;
            $insert[] = [
                'project_id' => $projectId,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'created_at' => $randomDate,
                'updated_at' => $randomDate,
            ];
        }

        // info that how many data will be inserted
        $this->info('Will insert ' . count($insert) . ' data');
        // ask if user want to insert
        if($this->confirm('Do you want to insert?')){
            ProjectView::insert($insert);
            $this->info('Data inserted successfully');
        }

        // info that how many data was inserted
        $this->info('Data inserted successfully');
    }
}
