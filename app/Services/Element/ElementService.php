<?php

namespace App\Services\Element;

use App\Models\Element;
use App\Models\Project;
use App\Services\Floor\FloorService;

class ElementService
{
    public function getElements(Project $project)
    {
        $elementsByFloors = (new FloorService)->getFloors($project, true);

        return $elementsByFloors;
    }

    public function relationService(Element $element, array $data): Element
    {
        if ($element->type == 'store') {
            $element->shop_information = (new StoreElement)->mutateElement($element, $data['shop_information'] ?? []);

            return $element;
        }

        return $element;
    }
}
