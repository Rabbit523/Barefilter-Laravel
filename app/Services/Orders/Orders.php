<?php

namespace App\Services\Orders;

use Illuminate\Support\Facades\Auth;
use App\Models\Core\User;
use App\Models\Orders\Order;
use App\Models\Orders\OrderSubscription;
use App\Models\Orders\Subscription;

use App\Services\Orders\Statistics;

class Orders
{

    public static function getDashboard($start, $end){
        return [
            "aggregates" => Statistics::getAggregates($start, $end),
            "sales_histogram" => Statistics::getSalesHistogram($start, $end),
            "top_ten" => Statistics::getMostSoldProducts(10, $start, $end)
        ];
    }

    public static function getProductsList($start, $end) {
        return Statistics::getSubscriptionsThisMonth($start, $end);
    }

    public static function search($q){
        $q = '%' . $q . '%';
        return  Order::where('id', 'like', $q)->with(["shipping", "user", "products.product", "products.subscription", "products.product.images"])->get();
    }

    public static function profile($id)
    {
        return Order::where('id', $id)->with(["shipping", "user", "products.product", "products.subscription"])->first();
    }
    
    public static function getHistory($uid)
    {
        $response["error"] = "No user found with id " . $uid;
        $user = User::find($uid);
        if ($user) {
            $response = ($user->role_id === User::$adminRole)
                    ? Order::orderBy('created_at', 'desc')->with(["shipping", "user", "products.product", "products.subscription"])->paginate(20)
                    : Order::where('user_id', $user->id)->orderBy('created_at', 'desc')->with(["shipping", "products.product", "products.subscription"])->paginate(20);
        }
        return $response;
    }


    public static function getTimeframedHistory($uid, $sid, $start, $end){
        $response["error"] = "No user found with id " . $uid;
        $user = User::find($uid);
        if ($user) {
            $query = ($user->role_id === User::$adminRole)
                    ? Order::whereDate('created_at', '>=', $start)->whereDate('created_at', '<=', $end)    
                    : Order::where('user_id', $user->id)->whereDate('created_at', '>=', $start)->whereDate('created_at', '<=', $end);
            
            $response = $query->with(["shipping", "user", "products" => function ($q) use($sid){
                if($sid !== '0'){
                    $q = $q->where('subscription_id', '=', $sid);
                }
                $q->with(["product", "subscription"]);
            }])->orderBy('created_at', 'desc')->get();
        }
        return $response;
    }

    public static function browseSubscriptions($start, $end){
        return OrderSubscription::whereDate('to_be_delivered_at', '>=', $start)
            ->whereDate('to_be_delivered_at', '<=', $end)
            ->with(["order.shipping", "orderProduct.product", "orderProduct.subscription", "orderProduct.product.category", "orderProduct.product.images"])
            ->orderBy('to_be_delivered_at', 'asc')
            ->get();
    }

    public static function getSubscriptionTypes(){
        return Subscription::get();
    }

    public static function getMyOneTimeTransactions($uid)
    {
        return self::getMyTransactionsBySubscriptionId($uid, [1]);
    }

    public static function getMySubscriptions($uid)
    {
        return self::getMyTransactionsBySubscriptionId($uid, [2, 3]);
    }

    public static function getMyOrders($bid)
    {
        return self::getMyTransactionsBySubscriptionId($uid, [2, 3]);
    }

    private static function getMyTransactionsBySubscriptionId($uid, $sid)
    {
        $response["error"] = "No user found with id " . join(',', $sid);
        $user = User::find($uid);
        if ($user) {
            if ($user->role_id === User::$adminRole) {
                $response = Order::orderBy('created_at', 'desc')
                    ->whereHas('products', function ($query) use ($sid) {
                        $query->whereIn('subscription_id', $sid);
                    })
                    ->with(['shipping', 'products' => function ($query)  {
                        $query->with(['product', 'product.images', 'subscription']);
                    }])
                    ->paginate(10);
            } else {
                $response = Order::where('user_id', $user->id)
                    ->orderBy('orders.created_at', 'desc')
                    ->whereHas('products', function ($query) use ($sid) {
                        $query->whereIn('subscription_id', $sid);
                    })
                    ->with(['shipping', 'products' => function ($query) use ($sid){
                        $query->whereIn('subscription_id', $sid)->with(['product', 'product.images', 'subscription']);
                    }])
                    ->paginate(10);
            }
        }
        return $response;
    }
}
