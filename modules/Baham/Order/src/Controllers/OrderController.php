<?php

namespace Baham\Order\Controllers;

use App\Http\Controllers\Controller;
use Baham\Order\Contract\OrderRepositoryInterface;
use Baham\Order\Models\Order;
use Baham\Order\Services\OrderService;
use Baham\Transaction\Models\Transaction;
use Baham\User\Contract\UserRepositoryInterface;
use Baham\User\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use function auth;
use function config;
use function resolve;
use function response;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function ordersForUserToFollow()
    {
        try {
            $orders = Cache::remember(
                'orders_to_follow', config('OrderConfig.order_cache_time'),
                function () {
                    return $this->orderService->getOrdersForUserToFollow(auth()->user());
                });
        } catch (\Throwable $exception) {

            return response()->json([
                'status' => false,
                'data' => null,
                'message' => $exception->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => true,
            'data' => [
                'orders' => $orders
            ],
            'message' => "لیست سفارشات با موفقیت دریافت شد."
        ], Response::HTTP_OK);
    }

    public function placeOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'count' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'status' => false,
                'data' => null,
                'message' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $orderPrice = $request->count * config('OrderConfig.order_registration_cost');
        if ($request->user()->coins < $orderPrice) {

            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'موجودی کاربر کافی نیست'
            ], Response::HTTP_NOT_ACCEPTABLE);
        }

        try {
            DB::beginTransaction();
            $orderRepo = resolve(OrderRepositoryInterface::class);

            $order = $orderRepo->create($request->count);
            resolve(UserRepositoryInterface::class)
                ->decreaseCoins($request->user(), $orderPrice);
            $orderRepo
                ->createTransaction($request->user()->id, $order->id , Transaction::TYPE_COST , $orderPrice);

            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'data' => null,
                'message' => $exception->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => true,
            'data' => [
                'order' => $order
            ],
            'message' => "سفارش با موفقیت ثبت شد"
        ], Response::HTTP_OK);
    }

    public function followOrder(Request $request, int $orderId)
    {
        $transaction = Transaction::where([
            'user_id' => $request->user()->id,
            'order_id' => $orderId
        ])->first();

        if ($transaction){
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => "کاربر قبلا این فرد را فالو کرده است"
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {

            DB::beginTransaction();

            $this->orderService->followOrder($request->user(), $orderId);

            DB::commit();
        }catch (\Throwable $exception){

            DB::rollBack();
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => $exception->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => true,
            'data' => null,
            'message' => "عملیات با موفقیت انجام شد"
        ], Response::HTTP_OK);
    }


}
