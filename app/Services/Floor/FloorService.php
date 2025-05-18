<?php

namespace App\Services\Floor;

use App\Models\Floor;

class FloorService
{
    public function createFloor(array $data): Floor
    {
        $floor = Floor::create($data);

        return $floor;
    }
}
