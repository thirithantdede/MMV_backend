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
        $shopInformationType = ['office','store','restaurant','cafe','anchor-store','kisok'];
        if (in_array($element->type, $shopInformationType)) {
            $element->shop_information = (new StoreElement)->mutateElement($element, $data['shop_information'] ?? []);
            return $element;
        }
        else if ($element->type =='event') {
            $element->event = (new EventService)->mutateElement($element, $data['event']?? []);
            return $element;
        }

        return $element;
    }

    public function deleteElement(string $id)
    {
        $element = Element::findOrFail($id);
        $element->delete();
        return true;
    }
}
