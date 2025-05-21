<?php

use Tests\Support\UserAuthenticated;

uses()->group('elements');

uses(UserAuthenticated::class);

uses()->beforeEach(function () {
    $this->setupUser();

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
        'floor' => 1,
        'type' => 'store',
    ];
    $this->actingAs($this->user);

});

it('create base element', function () {
    $service = app(\App\Services\Element\BaseElement::class);
    $element = $service->createBaseElement($this->data);
    $this->assertDatabaseHas('elements', [
        'id' => $element->id,
    ]);
});

it('can move element x and y and rotation', function () {
    $service = app(\App\Services\Element\BaseElement::class);
    $element = $service->createBaseElement($this->data);
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
