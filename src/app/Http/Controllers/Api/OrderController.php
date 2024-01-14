<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderStoreRequest;
use App\Http\Requests\OrderAssignRequest;
use App\Http\Requests\OrderCompleteRequest;
use App\Models\Courier;
use App\Models\Order;
use App\Http\Resources\OrderResource;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class OrderController extends Controller
{

    /**
     * The method for importing orders
     *
     * @api
     * @param  OrderStoreRequest  $request
     * @return JsonResponse
     */
    public function store(OrderStoreRequest $request)
    {
        $orders = [];
        foreach ($request->validated()['data'] as $item) {
            $orders[] = Order::add($item);
        }

        return response()->json([
            'orders' => OrderResource::collection($orders)
        ])->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * The method for assigning an order to a courier
     *
     * @api
     * @param  OrderAssignRequest  $request
     * @return JsonResponse|Response|ResponseFactory
     */
    public function assign(OrderAssignRequest $request)
    {
        $order = Order::getOrderForAssignedCourier(Courier::getDataCourier($request->courier_id));
        if (!empty($order)) {
            $order->assignCourierToOrder($request->courier_id);
            return response()->json([
                'order' => new OrderResource($order),
                'assign_time' => date('Y-m-d\\TH:i:s.vp', strtotime($order['assign_time']))
            ])->setStatusCode(Response::HTTP_OK);
        } else {
            return response(null)->setStatusCode(Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * The method for marking the completion of the order
     *
     * @api
     * @param  OrderCompleteRequest  $request
     * @return JsonResponse|Response|ResponseFactory
     */
    public function complete(OrderCompleteRequest $request)
    {
        $order = Order::whereNull('complete_time')
            ->where([['courier_id', $request->courier_id], ['id', $request->order_id]])->first();
        if (!empty($order)) {
            $order->update(['complete_time' => date('Y-m-d H:i:s', strtotime($request->complete_time))]);
            return response()->json([
                'order_id' => $order['id']
            ])->setStatusCode(Response::HTTP_OK);
        } else {
            return response(null)->setStatusCode(Response::HTTP_NOT_FOUND);
        }
    }
}
