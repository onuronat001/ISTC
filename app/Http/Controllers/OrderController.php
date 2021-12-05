<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Order;

class OrderController extends Controller
{
    
    public function index ()
    {
        $orderList = [];
        foreach (Order::all() as $order) {
            $order->items = json_decode($order->items);
            $orderList[] = $order;
        }

        return response ()->json ($orderList);
    }

    public function show ($orderId)
    {
        if (! $order = Order::where('id', '=', $orderId)->first()) {
            return response()->json(['result' => false, 'error' => sprintf('orderId: %s not found!', $orderId)]);
        }
        
        $order->items = json_decode($order->items);

        return response ()->json ($order);
    }

    public function insert ($customerId, Request $request)
    {
        if(! Customer::where ('id', '=', $customerId)->exists()) {
            return response()->json(['result' => false, 'error' => sprintf('customerId: %s not found!', $customerId)]);
        }

        $data = json_decode ($request->getContent());

        if(json_last_error () != JSON_ERROR_NONE) {
            return response()->json(['result' => false, 'error' => 'Data must be in json format! Example: {"productId": 1, "quantity": 1} or [{"productId": 1, "quantity": 1}, {"productId": 2, "quantity": 2}]'], 400);
        }

        if(!is_array ($data)) {
            $data = [$data];
        }

        $products = [];
        $items = [];
        $total = 0;
        
        foreach ($data as $obj) {
            if (! isset ($obj->productId) || (int) $obj->productId <= 0 || ! isset ($obj->quantity) || (int) $obj->quantity <= 0) {
                return response()->json(['result' => false, 'error' => 'Payload is invalid! Example: {"productId": 1, "quantity": 1} or [{"productId": 1, "quantity": 1}, {"productId": 2, "quantity": 2}]'], 400);
            }

            if (! $product = Product::where('id', '=', $obj->productId)->first()) {
                return response()->json(['result' => false, 'error' => sprintf('productId: %s not found!', $obj->productId)]);
            }

            if (! isset ($products[$product->id])) {
                $products[$product->id] = $product;
            }

            if ($products[$product->id]->stock < $obj->quantity) {
                return response()->json(['result' => false, 'error' => sprintf('productId: %s is out of stock!', $obj->productId)]);
            }

            $items[] = [
                'productId' => $product->id,
                'quantity'  => $obj->quantity,
                'unitPrice' => number_format($product->price, 2),
                'total'     => number_format($obj->quantity * $product->price, 2)
            ];

            $products[$product->id]->stock -= $obj->quantity;
            $total += $obj->quantity * $product->price;
        }

        foreach ($products as $id => $product) {
            Product::where('id', '=', $id)->update(['stock' => $product->stock]);
        }

        $orderId = Order::insertGetId([
            'customerId'    => $customerId,
            'items'         => json_encode ($items),
            'total'         => $total
        ]);

        return response()->json(['result' => true, 'orderId' => $orderId]);
    }

    public function delete ($orderId)
    {
        if(! $order = Order::where ('id', '=', $orderId)->first()) {
            return response()->json(['result' => false, 'error' => sprintf('orderId: %s not found!', $orderId)]);
        }

        $items = json_decode ($order->items);

        foreach ($items as $item) {
            Product::find($item->productId)->increment('stock', $item->quantity);
        }

        Order::where('id', '=', $orderId)->delete();

        return response()->json(['result' => true]);
    }

}
