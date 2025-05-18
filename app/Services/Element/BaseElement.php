<?php

namespace App\Services\Element;

use App\Models\Element;

class BaseElement
{
    public function prepareBaseElementData(array $data): array
    {
        return [
            'name' => $data['name'],
            'description' => $data['description'],
            'x' => $data['x'],
            'y' => $data['y'],
            'width' => $data['width'],
            'height' => $data['height'],
            'rotation' => $data['rotation'],
            'color' => $data['color'],
            'icon' => $data['icon'],
            'floor_id' => $data['floor_id'],
            'element_type_id' => $data['element_type_id'],
        ];
    }

    public function createBaseElement(array $data): Element
    {
        $data = $this->prepareBaseElementData($data);
        $element = Element::create($data);

        return $element;
    }

    public function updateBaseElement(Element $element, array $data): Element
    {
        $data = $this->prepareBaseElementData($data);
        $element->update($data);

        return $element;
    }
}
