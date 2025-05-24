<?php

namespace App\Services\Element;

use App\Models\Element;
use App\Models\ShopInformation;
use App\Models\StoreCategory;
use App\Traits\CacheResponse;
use Illuminate\Support\Facades\DB;

class StoreElement 
{

    public function mutateElement(Element $element, array $data): ShopInformation
    {   
       return DB::transaction(function () use ($element, $data) {
            $storeElement = ShopInformation::where('element_id', $element->id)->first();
            $storeData = $this->prepareData($data, $element);
            if ($storeElement) {
                $storeElement = $this->updateStoreInfo($storeElement, $storeData);
            } else {
                $storeElement = $this->createStoreInfo($storeData);
            }
            return $storeElement;   
       });
    }

    public function prepareData(array $data,Element $element): array
    {
        return [
            'name' => $data['name'] ?? "Shop 1",
            'description' => $data['description']?? "Shop Information",
            'contact_person' => $data['contact_person']?? "John Doe",
            'contact_phone' => $data['contact_phone']?? "1234567890",
            'contact_email' => $data['contact_email'] ?? "Email",
            'is_foc' => false,
            'opening_hours' => $data['opening_hours'] ?? "9:00 AM - 5:00 PM",
            "website" => $data['website']?? "Website",
            "social_media" => $data['social_media']?? "{}",
            "promotions" => $data['promotions']?? "{}",
            "closed_days" => $data['closed_days']?? "{}",
            "element_id" => $element->id,
            "store_category_id" => $data['store_category_id']?? StoreCategory::first()->id,
        ];
    }


    public function updateStoreInfo(ShopInformation $shopElement, array $data)
    {
        $shopElement->update($data);

        return $shopElement;
    }

    public function createStoreInfo(array $data)
    {
        $shopElement = ShopInformation::create($data);
        return $shopElement;
    }

    public function deleteStoreInfo(ShopInformation $shopElement)
    {
        $shopElement->delete();
        $baseElement = $shopElement->element;
        $baseElement->delete();

        return true;
    }
}
