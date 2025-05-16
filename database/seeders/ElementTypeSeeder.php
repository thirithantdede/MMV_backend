<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ElementTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = ['store', 'elevator', 'room', 'pathway', 'door', 'banner', 'event', 'info', 'atm', 'security', 'promotion', 'floor', 'stairs'];

        $floorTransition = ['elevator', 'stairs'];

        for ($i = 1; $i <= count($data); $i++) {
            $data[$i]['id'] = $i;
            $data[$i]['name'] = $data[$i];
            if (in_array($data[$i]['name'], $floorTransition)) {
                $data[$i]['is_floor_transition'] = true;
                $data[$i]['is_walkable'] = true;
                $data[$i]['is_store'] = false;
            } else {
                $data[$i]['is_floor_transition'] = false;
                $data[$i]['is_walkable'] = false;
                $data[$i]['is_store'] = true;
            }
        }

        \App\Models\ElementType::insert($data);
    }
}
