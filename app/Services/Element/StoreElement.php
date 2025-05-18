<?php

namespace App\Services\Element;

use App\Models\ShopInformation;
use App\Traits\CacheResponse;

class StoreElement extends BaseElement
{
    use CacheResponse;

    public function updateStoreInfo(ShopInformation $shopElement, array $data)
    {
        $shopElement->update($data);

        return $shopElement;
    }

    public function createStoreInfo(array $data)
    {
        $baseElement = $this->createBaseElement($data);

        $data['element_id'] = $baseElement->id;
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
