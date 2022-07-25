<?php

namespace App\Http\Controllers;

use App\Http\Requests\Orders\PrintOrdersRequest;
use App\Services\Orders\OrdersCollectionService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class TestController extends Controller
{
    /**
     * TODO: remove this controller
     * @param PrintOrdersRequest $request
     * @param OrdersCollectionService $ordersCollectionService
     * @return Factory|View|Application
     */
    public function index(
        PrintOrdersRequest $request,
        OrdersCollectionService $ordersCollectionService,
    ): Factory|View|Application {
        $orderIds = $request->validated();

        $requestParams = [
            'filter' => ['ids' => $orderIds['ids']],
        ];

        $orders = $ordersCollectionService->getInstances(
            requestParams: $requestParams,
            with: ['files:id,filename', 'manufacturer', 'seller', 'source']
        );

        return view('exports.orders.order_export_pdf', ['orders' => $orders]);
    }

}
