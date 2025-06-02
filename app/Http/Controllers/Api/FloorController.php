<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Services\Floor\FloorService;
use Illuminate\Http\Request;

class FloorController extends Controller
{
    public function createNewFloor(Request $request)
    {
        $floor = (new FloorService)->createFloor($request->all());
        return response()->json([
            'status' => 'success',
            'message' => 'Floor created successfully',
            'data' => $floor
        ]);
    }
}
