<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Helpers\Helpers;
use App\Http\Requests\CourierStoreRequest;
use App\Http\Requests\CourierUpdateRequest;
use App\Models\Courier;
use App\Http\Resources\CourierResource;
use Illuminate\Http\Response;
use \Illuminate\Http\JsonResponse;
use \Illuminate\Contracts\Routing\ResponseFactory;

class CourierController extends Controller
{

    /**
     * The method returns an entry from the couriers table by id
     *
     * @api
     * @param  int  $id
     * @return JsonResponse|Response|ResponseFactory
     */
    public function show($id)
    {
        $courier = Courier::find($id);
        if($courier == null)
        {
            return response(null)->setStatusCode(Response::HTTP_NOT_FOUND);
        }
        else{
            return response()
                ->json(new CourierResource($courier))
                ->setStatusCode(Response::HTTP_OK);
        }
    }

    /**
     * The method store entries to the couriers table
     *
     * @api
     * @param  CourierStoreRequest  $request
     * @return JsonResponse
     */
    public function store(CourierStoreRequest $request)
    {
        $couriers = [];
        foreach ($request->validated()['data'] as $item) {
            $couriers[] = Courier::add($item);
        }

        return response()->json([
            'couriers' => CourierResource::collection($couriers)
        ])->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * The method update entries to the couriers table
     *
     * @api
     * @param  CourierUpdateRequest  $request
     * @return JsonResponse|Response|ResponseFactory
     */
    public function update(CourierUpdateRequest $request, $id)
    {
        $courier = Courier::find($id);
        if($courier == null)
        {
            return response(null)
                ->setStatusCode(Response::HTTP_NOT_FOUND);
        }
        else{
            $courier->update($request->validated());
            $courier->updateRelatedTable($request->validated());
            return response()
                ->json(new CourierResource($courier))
                ->setStatusCode(Response::HTTP_OK, 'Created');
        }
    }
}
