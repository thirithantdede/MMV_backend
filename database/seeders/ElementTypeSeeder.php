<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ElementTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = ['store', 'elevator', 'room', 'pathway', 'door',
        'anchor-store','kisok',
        'banner', 'event', 'info', 'atm', 'security', 'promotion', 'floor', 'stairs','office',"restaurant","cafe"];
        $floorTransition = ['elevator', 'stairs','escalator'];

        $data = [];

        foreach ($names as $name) {
            $data[] = [
                'id' => (string) Str::ulid(),
                'name' => $name,
                'is_floor_transition' => in_array($name, $floorTransition),
                'is_walkable' => in_array($name, $floorTransition),
                'is_store' => ! in_array($name, $floorTransition),
            ];
        }

        \App\Models\ElementType::insert($data);
    }
}
