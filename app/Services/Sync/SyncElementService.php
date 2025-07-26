<?php

namespace App\Services\Sync;

use App\Events\AnalysicEvent;
use App\Models\Element;
use App\Services\Element\BaseElement;

class SyncElementService
{
    protected BaseElement $elementService;

    public function __construct()
    {
        $this->elementService = new BaseElement;
    }

    public function syncElements($elements)
    {
        $syncedElements = [];
        foreach ($elements as $element) {
                $syncedElements[] = $this->elementService->createOrUpdateElement($element);
        }

        return $syncedElements;
    }

    public function syncRoutes($fromElementId, $toElementId){
        $fromElement = Element::where('id',$fromElementId)->first();
        $toElement = Element::where('id',$toElementId)->first();

        if($fromElement){
            event(new AnalysicEvent($fromElement, "from-route", $fromElement, request()));
        }
        if($toElement){
            event(new AnalysicEvent($toElement, "to-route", $toElement, request()));
        }

        return true;

    }

    public function clarifyElments($elements)
    {
        $clarifiedElements = [];

    }

    public function cleanElements()
    {
        $elements = Element::where('project_id', auth()->user()->project->id)->delete();   
        return true;
    }
}
