<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrdersController extends Controller
{
    /**
     * Display a listing of orders.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $orders = Order::with(['user', 'orderDetails', 'orderDetails.product', 'payment'])->get();
            $orders = OrderResource::collection($orders);

            if ($orders->isEmpty()) {
                return ApiResponseHelper::resData($orders, 'No orders found');
            }

            return ApiResponseHelper::resData($orders, 'Orders retrieved successfully');
        } catch (\Exception $e) {
            return ApiResponseHelper::resError('Error fetching orders', $e->getMessage());
        }
    }

    /**
     * Display the specified order.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $order = Order::with(['user', 'orderDetails', 'orderDetails.product', 'payment'])->findOrFail($id);
            $orderResource = new OrderResource($order);

            return ApiResponseHelper::resData($orderResource, 'Order details retrieved successfully');
        } catch (\Exception $e) {
            return ApiResponseHelper::resError('Error fetching order details', $e->getMessage());
        }
    }

    /**
     * Accept the specified order.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function accept($id)
    {
        try {
            $order = Order::findOrFail($id);
            $order->status = 'accepted';  // Adjust status as needed
            $order->save();

            return ApiResponseHelper::resData(new OrderResource($order), 'Order accepted successfully');
        } catch (\Exception $e) {
            return ApiResponseHelper::resError('Error accepting order', $e->getMessage());
        }
    }

    /**
     * Reject the specified order.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function reject($id)
    {
        try {
            $order = Order::findOrFail($id);
            $order->status = 'rejected';  // Adjust status as needed
            $order->save();

            return ApiResponseHelper::resData(new OrderResource($order), 'Order rejected successfully');
        } catch (\Exception $e) {
            return ApiResponseHelper::resError('Error rejecting order', $e->getMessage());
        }
    }
}
