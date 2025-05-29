<?php

namespace App\Services\Element;

use App\Models\Element;
use App\Models\Event;
use Illuminate\Support\Facades\DB;

class EventService
{
    public function mutateElement(Element $element, array $data): Event
    {
        return DB::transaction(function () use ($element, $data) {
            $eventElement = Event::where('id', $data['id'] ?? null)->first();
            $storeData = $this->prepareData($data, $element);
            if ($eventElement) {
                $eventElement = $this->updateStoreInfo($eventElement, $storeData);
            } else {
                $eventElement = $this->createStoreInfo($storeData);
            }
            return $eventElement;
        });
    }

    public function prepareData(array $data, Element $element): array
    {
        return [
            'element_id' => $element->id,
            'title' => $data['title'] ?? "New Event",
            'description' => $data['description'] ?? '',
            'company' => $data['company'] ?? 'Blue Stone Company',
            'hosts' => $data['description'] ?? 'Aung Kyaw,Myo Kyaw',
            'start_date' => $data['start_date'] ?? now(),
            'end_date' => $data['end_date'] ?? now(),
            'start_time' => $data['start_time']?? "19:00",
            'end_time' => $data['end_time']?? "21:00",
            'is_foc' => $data['is_foc'] ?? false,
            'is_active' => $data['is_active']?? false,
            'image_url' => $data['image_url']?? '',
            'is_featured' => $data['is_featured']?? false,
        ];
    }

    public function updateStoreInfo(Event $eventElement, array $data)
    {
        $eventElement->update($data);

        return $eventElement;
    }

    public function createStoreInfo(array $data)
    {
        $shopElement = Event::create($data);

        return $shopElement;
    }

    public function deleteStoreInfo(Event $shopElement)
    {
        $shopElement->delete();
        $baseElement = $shopElement->element;
        $baseElement->delete();

        return true;
    }
}
