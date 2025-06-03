<?php

namespace App\Services\Element;

use App\Events\AnalysicEvent;
use App\Models\Element;
use App\Models\ElementType;
use App\Models\Floor;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BaseElement
{

    public function searchElements(Request $request, Project $project): array {
        $query = Element::with( 'shopInformation')->where('project_id', $project->id);
        $search = $request->input('search');
        $floor = $request->input('floor');
        $category = trim($request->input('category'));

        $search = trim(strtolower($search)); // normalize
    
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(name) LIKE ?', ["%$search%"])
                  ->orWhereRaw('LOWER(description) LIKE ?', ["%$search%"])
                  ->orWhereHas('shopInformation', function ($subQuery) use ($search) {
                      $subQuery->whereRaw('LOWER(name) LIKE ?', ["%$search%"]);
                  });
            });
        }

        if(isset($floor) && $floor != "all"){
            $floorModel = Floor::where('project_id', $project->id)->where('level', $floor)->firstOrFail();
            $query->where('floor_id', $floorModel->id);
        }

        if(isset($category) && $category != "" && $category != "all"){
            $query->whereHas('shopInformation', function ($subQuery) use ($category) {
                $subQuery->whereHas('storeCategory', function ($subSubQuery) use ($category) {
                    $subSubQuery->where('name', $category);
                }); 
            });
        }   

        $elements = $query->limit(20)->get();

        if(!empty($search)){
            foreach ($elements as $element) {
                event(new AnalysicEvent($element, "search", $search, $request));
            }
        }

        return $elements->toArray();
    }
    



    public function createOrUpdateElement(array $elementData): Element 
    {
        return DB::transaction(function () use ($elementData) {

            // $floor = Floor::where('project_id', auth()->user()->project->id)->where('level', $elementData['floor'])->first();
            // if($floor){
            //     $elementCount = Element::where('x', $elementData['x'])
            //     ->where('y', $elementData['y'])
            //     ->where('floor_id', $floor->id)
            //     ->get();
            //         if (count($elementCount) > 1) {
            //             return $elementCount[0];
            // }
        //    }
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

            return (new ElementService)->relationService($element, $elementData);
        });
    }

    public function prepareBaseElementData(array $data): array
    {
        $authUser = auth()->user();
        if ($data['floor'] != 0) {
            $floor = Floor::where('project_id', $authUser->project->id)->where('level', $data['floor'])->firstOrFail();
        } else {
            $floor = (object) ['id' => 0];
        }
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
            'border_color' => $data['border_color'] ?? '#000000',
            'icon' => $data['icon'] ?? 'FA',
            'border_style' => $data['border_style'] ?? 'solid',
            'floor_id' => $floor->id,
            'project_id' => $authUser->project->id,
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

    public function deleteElement(string $id)
    {
        $element = Element::find($id);
        if($element){
            $element->delete();
            return true;
        }
        return false;
    }
}
