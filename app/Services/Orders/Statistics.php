<?php

namespace App\Services\Orders;

use Illuminate\Support\Facades\DB;

use App\Models\Orders\Order;
use App\Models\Orders\OrderProduct;
use App\Models\Orders\Subscription;
use App\Models\Orders\OrderSubscription;

use App\Models\Stores\Product;

class Statistics
{

    public static function getAggregates($start, $end){

        $query = Order::whereDate('created_at', '>=', $start)->whereDate('created_at', '<=', $end)->where('status', '<=', '3')->where('status', '>=', '2');
        
        $response = $query->with(["products" => function ($q){
            $q->with(["product", "subscription"]);
        }])->get();
        $total_onetime_purchases = 0;
        $total_filter_package = 0;
        foreach ($response as $order) {
            foreach ($order->products as $product) {
                $total_filter_package += $product->amount;
                if ($product->subscription_id == 1) {
                    $total_onetime_purchases++;
                }
            }
        }
        return [
            "orders" => Order::whereDate('created_at','>=', $start)->whereDate('created_at', '<=', $end)->where('status', '<=', '3')->where('status', '>=', '2')->count(),
            "sales" => Order::whereDate('created_at','>=', $start)->whereDate('created_at', '<=', $end)->where('status', '<=', '3')->where('status', '>=', '2')->sum('total'),
            "discounts" => Order::whereDate('created_at','>=', $start)->whereDate('created_at', '<=', $end)->where('status', '<=', '3')->where('status', '>=', '2')->sum('discount'),
            "total_onetime_purchases" => $total_onetime_purchases,
            "filter_packages_purchases" => $total_filter_package,
            "total_subscriptions" => Order::whereDate('created_at','>=', $start)->whereDate('created_at', '<=', $end)->where('status', '<=', '3')->where('status', '>=', '2')
                    ->whereHas('products', function ($query) {
                        $query->whereIn('subscription_id', ['2', '3']);
                    })->count(),
            "total_onetime_subscriptions" => Order::whereDate('created_at','>=', $start)->whereDate('created_at', '<=', $end)->where('status', '<=', '3')->where('status', '>=', '2')
                    ->whereHas('products', function ($query) {
                        $query->whereIn('subscription_id', ['2']);
                    })->count(),
            "total_twotime_subscriptions" => Order::whereDate('created_at','>=', $start)->whereDate('created_at', '<=', $end)->where('status', '<=', '3')->where('status', '>=', '2')
                    ->whereHas('products', function ($query) {
                        $query->whereIn('subscription_id', ['3']);
                    })->count(),
            "total_deleted_subscriptions"
        ];
    }

    public static function getSalesHistogram($start, $end){
        $first = date("Y-01-01");
        $last = date("Y-12-31");
        $begin = date("1990-01-01");
        if (strcmp($start, $begin)==0) {
            $days = Order::whereDate('created_at','>=', $start)->whereDate('created_at', '<=', $end)->where('status', '<=', '3')->where('status', '>=', '2')
            ->get()
            ->groupBy(function($item){ return $item->created_at->format('Y'); });
            $histogram = array();
            foreach($days as $date => $orders){
                $values = ["date" => $date, "sales" => 0, "discounts" => 0];
                foreach($orders as $order){
                    $values["sales"] += $order->total;
                    $values["discounts"] += $order->discount;
                }
                array_push($histogram, $values);
            }
            return $histogram;
        } else if ((strcmp($start, $first)==0) && (strcmp($last, $end)==0)) {
            $days = Order::whereDate('created_at','>=', $start)->whereDate('created_at', '<=', $end)->where('status', '<=', '3')->where('status', '>=', '2')
            ->get()
            ->groupBy(function($item){ return $item->created_at->format('Y-m'); });
            $histogram = array();
            foreach($days as $date => $orders){
                $month = date("M", strtotime($date));
                $values = ["date" => $month, "sales" => 0, "discounts" => 0];
                foreach($orders as $order){
                    $values["sales"] += $order->total;
                    $values["discounts"] += $order->discount;
                }
                array_push($histogram, $values);
            }
            return $histogram;
        } else {
            $days = Order::whereDate('created_at','>=', $start)->whereDate('created_at', '<=', $end)->where('status', '<=', '3')->where('status', '>=', '2')
            ->get()
            ->groupBy(function($item){ return $item->created_at->format('Y-m-d'); });

            $histogram = array();
            foreach($days as $date => $orders){
                $values = ["date" => $date, "sales" => 0, "discounts" => 0];
                foreach($orders as $order){
                    $values["sales"] += $order->total;
                    $values["discounts"] += $order->discount;
                }
                array_push($histogram, $values);
            }
            return $histogram;
        }
    }

    public static function getMostSoldProducts($limit, $start, $end){
        $data = OrderProduct::whereDate('created_at','>=', $start)->whereDate('created_at', '<=', $end)
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('products')
                        ->whereRaw('products.id = order_products.product_id');
                })
                ->select('product_id', DB::raw('count(*) as total'))
                ->groupBy('product_id')
                ->orderByRaw('total DESC')
                ->take($limit)
                ->get();
        foreach($data as $bin){
            $bin->product = Product::where('id', $bin->product_id)
                ->select(["id", "category_id", "name", "sku", "price", "handle"])
                ->with(["category.type", "images"])
                ->first();
        }
        return $data;
    }

    public static function getSubscriptionsThisMonth($start, $end){
        // $data = OrderProduct::whereDate('created_at','>=', $start)->whereDate('created_at', '<=', $end)
        //         ->whereIn('subscription_id', ['2', '3'])
        //         ->select('product_id', DB::raw('count(*) as total'))
        //         ->groupBy('product_id')
        //         ->get();
        // foreach($data as $bin){
        //     $bin->product = Product::where('id', $bin->product_id)
        //         ->select(["id", "category_id", "name", "sku", "price", "handle"])
        //         ->with(["category.type", "images"])
        //         ->first();
        // }        
        // return OrderSubscription::whereDate('to_be_delivered_at', '>=', $start)
        // ->whereDate('to_be_delivered_at', '<=', $end)
        // ->with(["order.shipping", "orderProduct.product", "orderProduct.subscription", "orderProduct.product.category", "orderProduct.product.images"])
        // ->orderBy('to_be_delivered_at', 'asc')
        // ->groupBy('order_product_id')
        // ->get();

        // return DB::table('order_subscriptions')->whereDate('to_be_delivered_at', '>=', $start)
        //     ->whereDate('to_be_delivered_at', '<=', $end)
        //     ->join('order_products', function($join) {
        //         $join->on('order_products.id', '=', 'order_product_id')
        //             ->join('products', function($join) {
        //                 $join->on('products.id', '=', 'order_products.product_id');
        //             });
                // ->join('products', 'products.id', '=', 'order_products.product_id');
            // })
            // ->join('order_products', 'order_product_id', '=', 'order_products.id')
            // ->join('product_categories', 'product_categories.id', '=', 'products.category_id')
            // ->join('product_images', 'product_images.product_id', '=', 'products.id')
            // ->groupBy('order_products.product_id')
            // ->select('*', DB::raw('sum(order_products.amount) as orderproducts_total'), 'product_categories.name as category_name')
            // ->select('*')
            // ->get();
        return OrderSubscription::whereDate('to_be_delivered_at', '>=', $start)
            ->whereDate('to_be_delivered_at', '<=', $end)
            ->with(["order.shipping", "orderProduct.product", "orderProduct.subscription", "orderProduct.product.category", "orderProduct.product.images"])
            ->orderBy('to_be_delivered_at', 'asc')
            ->get();
        return $data;
    }
}