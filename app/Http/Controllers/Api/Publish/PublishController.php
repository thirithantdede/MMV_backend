<?php

namespace App\Http\Controllers\Api\Publish;

use App\Http\Controllers\Controller;
use App\Services\Publish\PublishService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PublishController extends Controller
{
    public function viewProject(Request $request): JsonResponse
    {
        $data = (new PublishService)->getData($request);
        return responseJson($data);
    }

    public function projectLists(Request $request): JsonResponse
    {
        $data = (new PublishService)->getProjectLists($request);
        return responseJson(['data' => $data]);
    }

    public function publishProject(Request $request): JsonResponse
    {
        $data = (new PublishService)->publish($request);
        unset($data['floors']);
        return responseJson([
            'message' => 'Project published successfully',
           'data' => $data
        ]);
    }
}
