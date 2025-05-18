<?php

use App\Models\ElementType;
use App\Models\Floor;

uses()->group('elements');

uses()->beforeEach(function () {
    $this->data = [
        'name' => 'test',
        'description' => 'test',
        'x' => 1,
        'y' => 1,
        'width' => 1,
        'height' => 1,
        'rotation' => 0,
        'color' => '#000000',
        'icon' => 'test',
        'floor_id' => Floor::inRandomOrder()->first()->id,
        'element_type_id' => ElementType::inRandomOrder()->first()->id,
    ];
});

it('create base element', function () {
    $service = app(\App\Services\Element\BaseElement::class);
    $element = $service->createBaseElement($this->data);
    $this->assertDatabaseHas('elements', $this->data);
    $this->assertInstanceOf(\App\Models\Element::class, $element);
});

it('can move element x and y and rotation', function () {
    $service = app(\App\Services\Element\BaseElement::class);
    $element = $service->createBaseElement($this->data);
    $this->assertDatabaseHas('elements', $this->data);
    $this->assertInstanceOf(\App\Models\Element::class, $element);
    $element->x = 2;
    $element->y = 2;
    $element->rotation = 90;
    $element->save();
    $this->assertDatabaseHas('elements', [
        'x' => 2,
        'y' => 2,
        'rotation' => 90,
    ]);
});
