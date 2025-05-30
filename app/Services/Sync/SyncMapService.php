<?php

namespace App\Services\Sync;

use App\Models\BuildingFootprint;

class SyncMapService
{
    public function syncMap(BuildingFootprint $buildingFootprint, array $data)
    {
        $buildingFootprint->update([
            'width' => $data['width'],
            'height' => $data['height'],
            'building_x' => $data['building_x'],
            'building_y' => $data['building_y'],
            'building_width' => $data['building_width'],
            'building_height' => $data['building_height'],
            'restricted' => $data['restricted'],
            'grid_size' => $data['grid_size'],
            'show_opening_hours' => $data['show_opening_hours'] ?? false,
            'show_grid' => $data['show_grid'],
        ]);

        return $buildingFootprint;
    }
}
