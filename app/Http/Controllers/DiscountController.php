<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;

class DiscountController extends Controller
{
    public function index ($orderId)
    {
        if(! $order = Order::where ('id', '=', $orderId)->first()) {
            return response()->json(['result' => false, 'error' => sprintf('orderId: %s not found!', $orderId)]);
        }

        $discounts = [];
        $totalDiscount = 0;

        if ($order->total > 1000) {
            $discountAmount = $order->total * .1;
            $order->total -= $discountAmount;
            $totalDiscount += $discountAmount;

            $discounts[] = [
                'discountReason'    => '10_PERCENT_OVER_1000',
                'discountAmount'    => number_format ($discountAmount, 2),
                'subtotal'          => number_format ($order->total, 2)
            ];
        }

        $items = json_decode ($order->items);

        $products = [];

        foreach ($items as $item) {
            if (! isset ($products[$item->productId])) {
                $products[$item->productId] = $item;
            }
            else {
                $products[$item->productId]->total += $item->total;
                $products[$item->productId]->quantity += $item->quantity;
            }
        }

        $categories = [];

        foreach (Product::whereIn('id', array_keys ($products))->get() as $product) {
            $categories[$product->category][] = $product;

            if ($product->category == 2 && $products[$product->id]->quantity >= 6) {
                $freeProduct = ceil ($products[$product->id]->quantity / 6);

                for ($i = 0; $i < $freeProduct; $i++) {
                    $discountAmount = $products[$product->id]->unitPrice;
                    $order->total -= $discountAmount;
                    $totalDiscount += $discountAmount;

                    $discounts[] = [
                        'discountReason'    => 'BUY_5_GET_1',
                        'discountAmount'    => number_format ($discountAmount, 2),
                        'subtotal'          => number_format ($order->total, 2)
                    ];
                }
                
            }

        }


        if (is_array ($categories['1']) && count ($categories['1']) >= 2) {
            $compared = $categories['1'];
            
            foreach ($compared as $index => $product) {
                if (isset ($compared[$index + 1])) {
                    $lowest = $product->price < $compared[$index + 1]->price ? $product : $compared[$index + 1];
                }
            }

            $discountAmount = $products[$lowest->id]->unitPrice * .2;
            $order->total -= $discountAmount;
            $totalDiscount += $discountAmount;

            $discounts[] = [
                'discountReason'    => '20_PERCENT_LOWEST_PRODUCT',
                'discountAmount'    => number_format ($discountAmount, 2),
                'subtotal'          => number_format ($order->total, 2)
            ];
        }

        return [
            'orderId'           => $orderId,
            'discounts'         => $discounts,
            'totalDiscount'     => number_format ($totalDiscount, 2),
            'discountedTotal'   => number_format ($order->total, 2)
        ];

    }
}
