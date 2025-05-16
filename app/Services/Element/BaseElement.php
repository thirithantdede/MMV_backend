<?php

namespace App\Services\Element;

use App\Models\Element;
use App\Traits\CacheResponse;

class BaseElement
{
    use CacheResponse;

    public function createBaseElement(array $data): Element
    {
        $element = Element::create($data);

        return $element;
    }

    public function updateBaseElement(Element $element, array $data): Element
    {
        $element->update($data);

        return $element;
    }
}
