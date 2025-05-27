<?php

namespace App\Services\Sync;

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

    public function clarifyElments($elements)
    {
        $clarifiedElements = [];

    }
}
