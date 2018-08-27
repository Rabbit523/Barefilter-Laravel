<?php

namespace App\Services\Orders;

use Illuminate\Support\Facades\Auth;
use App\Models\Core\User;
use App\Models\Core\Task;
use App\Models\Orders\Order;
use App\Models\Orders\OrderProduct;
use App\Models\Orders\OrderSubscription;
use App\Models\Partners\BuildingOrder;

use App\Services\Core\Users;
use App\Services\Core\Tasks;
use App\Services\Core\Addresses;
use App\Services\Apis\Netaxept;

class Manager
{
    private static function none()
    {
        $data = [
            "uid" => "", // (optional)
            "service_partner" => "", // (optional)
            "netaxept" => true,
            "tas" => "",
            "promo_code" => "", // (optional)
            "products" => [],
            "addresses" => [
                "same" => true,
                "shipping" => "",
                "billing" => ""
            ],
            "summary" => [
                "total" => 0,
                "discount" => 0
            ]
        ];
    }
    public static function transferSubscription($data)
    {
        $op = OrderProduct::find($data['opid']);
        if($op){
            $op->subscription_id = (int) $data["sid"];
            $op->amount = $data["amount"];
            $op->save();
            $orderSubscription = OrderSubscription::where(["order_id" => $op->order_id, "order_product_id" => $op->id])->first();
            self::processSubscription(Order::find($op->order_id), $op);
        }
        return $op;
    }

    public static function SubScriptionDeleteOrder($data){
        $order = OrderSubscription::find($data["id"]);
        $response = array("error" => "No such order!");
        if($order){
            $response = array("subscription-order" => $order->id);
            BuildingOrder::where('id', $order->id)->delete();
            $order->delete();
        }
        return $response;
    }
    public static function cancelSubscription($data)
    {
        $op = OrderProduct::find($data['opid']);
        if($op){
            $op->subscription_id = 1;
            $op->save();
            OrderSubscription::where(["order_id" => $op->order_id, "order_product_id" => $op->id])->delete();
        }
        return $op;
    }

    public static function placeOrder($data)
    {
        $response = array("redirect" => route("order-completed"));
        $order = self::createOrder($data);
        if ($data["netaxept"] === "true") {
            $netaxept = Netaxept::register($order->identifier, $order->total);
            $order->transaction_id = $netaxept["TransactionId"];
            $response["redirect"] = Netaxept::terminal($order->transaction_id);
        } else {
            $order->status = Order::$unpaid;
            self::createBackgroundTasks($order);
        }
        $order->save();
        $response["order"] = $order;
        return $response;
    }


    public static function softDeleteOrder($data){
        $order = Order::find($data["oid"]);
        $response = array("error" => "No such order!");
        if($order){
            $response = array("order" => $order);
            BuildingOrder::where('order_id', $order->id)->delete();
            $order->delete();
        }
        return $response;
    }


    public static function deleteUserOrders($uid){
        $orders = Order::where('user_id', $uid)->get();
        foreach($orders as $order){
            self::deleteOrder($order->id);
        }
    }

    public static function deleteOrder($oid){
        $order = Order::find($oid);
        BuildingOrder::where('order_id', $order->id)->forceDelete();
        OrderProduct::where('order_id', $order->id)->delete();
        return $order->forceDelete();
    }

    public static function processNetaxeptPayment($transactionId, $responseCode)
    {
        $order = Order::where('transaction_id', $transactionId)->first();
        $result["success"] = false;
        if ($order && $order->status === Order::$unprocessed && $responseCode == "OK") {
            //$order->status = Order::$paid;
            $order->save();
            $result["success"] = true;
            $result["order"] = $order;
            self::createBackgroundTasks($order, true);
        } else {
            if ($order) {
                $order->status = ($order->status === Order::$unprocessed) ? Order::$paymentFailed : $order->status;
                $order->save();
                $result["error"] = ($order->status === Order::$unprocessed) ? "failed" : "already-processed";
            } else {
                $result["error"] = "No such order!";
            }
        }
        return $result;
    }
    

    public static function discoverSubscriptions(){
        $orders = Order::with(["products"])->get();
        $subscriptions = array();
        foreach($orders as $order){
            foreach($order->products as $product){
                $subscriptions = array_merge($subscriptions, self::processSubscription($order, $product));
            }
        }
        return $subscriptions;
    }

    private static function createOrder($data)
    {
        $user = Users::processOrderUser($data);
        $addresses = Addresses::processOrderAddresses($user->id, $data["addresses"]);
        $properties = [
            "tas" => $data["tas"],
            "promocode" => (isset($data["promo_code"])) ? $data["promo_code"] : null,
            "netaxept" => (isset($data["netaxept"])) ? $data["netaxept"] : false,
            "service_partner" => (isset($data["service_partner"])) ? $data["service_partner"] : null,
            "company" => (isset($data["company"])) ? $data["company"] : null,
            "summary" => $data["summary"],
            "notes" => (isset($data["notes"])) ? $data["notes"] : null,
            "guest" => (isset($data["guest"])) ? true : false
        ];
        $order = Order::create([
            "user_id" => $user->id,
            "shipping_method_id" => $data["shipping_method_id"],
            "payment_method_id" => $data["payment_method_id"],
            "transaction_id" => "",
            "consignment_id" => "",
            "shipping_id" => $addresses["shipping"]->id,
            "billing_id" => $addresses["billing"]->id,
            "status" => Order::$unprocessed,
            "discount" => $data["summary"]["discount"],
            "total" => $data["summary"]["total"],
            "properties" => json_encode($properties)
        ]);
        if(isset($data["bid"]) && isset($data["fid"])){
            BuildingOrder::create([
                "order_id" => $order->id,
                "building_id" => $data["bid"],
                "facility_id" => $data["fid"],
            ]);
        }else{
            $user->shipping_id = $addresses["shipping"]->id;
            $user->billing_id = $addresses["billing"]->id;
            $user->save();
        }
        self::addOrderProducts($order, $data["products"]);
        return $order;
    }

    private static function addOrderProducts($order, $products){
        foreach ($products as $product) {
            $orderProduct = OrderProduct::create([
                "order_id" => $order->id,
                "product_id" => $product["id"],
                "subscription_id" => $product["subscription_id"],
                "amount" => $product["total"]
            ]);
            self::processSubscription($order, $orderProduct);
        }
    }

    private static function processSubscription($order, $orderProduct){
        $date = new \DateTime($order->created_at);
        $subscriptions = array();
        if($orderProduct->subscription_id === 2){
            array_push($subscriptions, self::addOrderSubscription($order->id, $orderProduct->id, $date->modify('+12 month')));
        }elseif($orderProduct->subscription_id === 3){
            array_push($subscriptions, self::addOrderSubscription($order->id, $orderProduct->id, $date->modify('+6 month')));
            array_push($subscriptions, self::addOrderSubscription($order->id, $orderProduct->id, $date->modify('+6 month')));
        }
        return $subscriptions;
    }

    private static function addOrderSubscription($oid, $opid, $delivery){
        $format = "Y-m-d H:i:s";
        return OrderSubscription::create([
            "order_id" => $oid,
            "order_product_id" => $opid,
            "processed" => false,
            "to_be_delivered_at" => $delivery->format($format)
        ]);
    }

    private static function createBackgroundTasks($order, $netaxept = false){
        $properties = ["order_id" => $order->id];
        if($netaxept){
            Tasks::add(Task::$automateNetaxept, $properties);
        }else{
            if(!$order->properties->guest){
                Tasks::add(Task::$orderConfirmationEmail, $properties);
            }
            Tasks::add(Task::$newOrderNotificationEmail, $properties);
            Tasks::add(Task::$automateCargonizer, $properties);
        }
    }
}
