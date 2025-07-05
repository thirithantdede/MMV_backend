<?php

namespace App\Console\Commands;

use App\Models\Element;
use App\Models\Project;
use Illuminate\Console\Command;

class DummyEventList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:events {project_id} {count}';

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

        $elements = Element::where('project_id', $projectId)->get();

        $events = Event::where('project_id', $projectId)->get();
        $this->info('Events: ' . $events->count());
    }
}
