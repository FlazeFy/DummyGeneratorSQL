<?php

namespace App\Http\Controllers\Multi_API;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\Factories;

class ColumnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function getFactoryByIdType(Request $request, $id)
    {
        try{
            $res = Factories::getFactoryByIdType($id);

            return response()->json([
                'status' => 'success',
                'message' => 'data fetched',
                'data' => $res
            ], Response::HTTP_OK);
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
