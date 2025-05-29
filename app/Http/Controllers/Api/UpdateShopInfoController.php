<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ShopInformation;
use App\Services\Element\StoreElement;
use Illuminate\Http\Request;

class UpdateShopInfoController extends Controller
{

    public function getShop(Request $request){
        $shop_information = ShopInformation::where("readable_id",$request->id)->firstOrFail();
        return responseJson(['shop_information' => $shop_information]);
    }


    public function getShopInfo(Request $request)
    {
        $shop_information = ShopInformation::where("readable_id",$request->shop_id)->where("id",$request->shop_token)->firstOrFail();

        return responseJson(['shop_information' => $shop_information]);
    }

    public function updateShopInfo(Request $request)
    {
        $shop_information = ShopInformation::with('element')->where("id",$request->id)->firstOrFail();
        $data = (new StoreElement)->prepareData($request->all(),$shop_information->element);
        (new StoreElement)->updateStoreInfo($shop_information,$data);
        return responseJson(['shop_information' => $shop_information]);
    }
}
