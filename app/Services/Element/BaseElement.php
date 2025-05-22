<?php

namespace App\Services\Element;

use App\Models\Element;
use App\Models\ElementType;
use App\Models\Floor;
use Illuminate\Support\Facades\DB;

class BaseElement
{
    public function createOrUpdateElement(array $elementData): Element
    {
        return DB::transaction(function () use ($elementData) {
            if (str_starts_with($elementData['id'], 'new_element')) {
                $element = $this->createBaseElement($elementData);
                $element->old_element_id = $elementData['id'];
            } else {
                $element = Element::where('id', $elementData['id'])->first();
                if (! $element) {
                    $element = $this->createBaseElement($elementData);
                    $element->old_element_id = $elementData['id'];
                } else {
                    $element = $this->updateBaseElement($element, $elementData);
                }
            }

            return $element;
        });
    }

    public function prepareBaseElementData(array $data): array
    {
        $authUser = auth()->user();
        $floor = Floor::where('project_id', $authUser->project->id)->where('level', $data['floor'])->firstOrFail();
        $elementType = ElementType::where('name', $data['type'])->first();

        return [
            'name' => $data['name'],
            'x' => $data['x'] ?? 100,
            'y' => $data['y'] ?? 100,
            'width' => $data['width'] ?? 80,
            'height' => $data['height'] ?? 80,
            'rotation' => $data['rotation'] ?? 0,
            'opacity' => $data['opacity'] ?? 100,
            'color' => $data['color'] ?? '#000000',
            'icon' => $data['icon'] ?? 'FA',
            'floor_id' => $floor->id,
            'element_type_id' => $elementType->id,
            'border_radius' => json_encode($data['border_radius'] ?? ['topLeft' => 0, 'topRight' => 0, 'bottomRight' => 0, 'bottomLeft' => 0]),
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
