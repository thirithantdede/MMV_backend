<?php

namespace App\Http\Controllers\Api\Publish;

use App\Http\Controllers\Controller;
use App\Services\Publish\PublishService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PublishController extends Controller
{
    public function publish(Request $request): JsonResponse
    {
        $data =  (new PublishService)->getData($request);
        return responseJson($data);
    }
}
