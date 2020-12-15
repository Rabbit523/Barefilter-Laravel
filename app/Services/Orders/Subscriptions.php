<?php

namespace App\Services\Orders;

use App\Models\Orders\Order;
use App\Models\Orders\OrderProduct;
use App\Models\Orders\OrderSubscription;
use App\Models\Orders\ShippingMethod;
use App\Models\Orders\PaymentMethod;
use app\Models\Core\Setting;

use App\Models\Core\Task;
use App\Services\Core\Tasks;

class Subscriptions{


    public static function today(){
        $orderSubscriptions = self::getSubscriptionsForToday();
        $newOrders = array();
        foreach($orderSubscriptions as $orderSubscription){
            if($orderSubscription->order !== null){
                $order = self::placeOrder($orderSubscription);
                self::createBackgroundTasks($order);
                array_push($newOrders, $order);
            }
            $orderSubscription->processed = true;
            $orderSubscription->save();
        }
        return $newOrders;
    }

    public static function monthly(){
        $orderSubscriptions = self::getSubscriptionsForThisMonth();
        $newOrders = array();
        foreach($orderSubscriptions as $orderSubscription){
            if($orderSubscription->order !== null){
                $order = self::placeOrder($orderSubscription);
                $orderSubscription->processed = true;
                $orderSubscription->save();
                self::createBackgroundTasks($order);
                array_push($newOrders, $order);
            }
        }
        return $orderSubscriptions;
    }

    private static function getSubscriptionsForToday(){
        $date = new \DateTime();
        $format = "Y-m-d";
        return OrderSubscription::whereDate('to_be_delivered_at', '>=', $date->format($format))
            ->whereDate('to_be_delivered_at', '<=', $date->modify('+1 day')->format($format))
            ->where('processed', '=', false)
            ->with(["order.shipping", "orderProduct.product", "orderProduct.subscription"])
            ->orderBy('to_be_delivered_at', 'asc')
            ->get();
    }

    private static function getSubscriptionsForThisMonth(){
        $date = new \DateTime();
        $format = "Y-m-d";
        return OrderSubscription::whereDate('to_be_delivered_at', '>=', $date->modify('first day of this month')->format($format))
            ->whereDate('to_be_delivered_at', '<=', $date->modify('last day of this month')->format($format))
            ->where('processed', '=', false)
            ->with(["order.shipping", "orderProduct.product", "orderProduct.subscription"])
            ->orderBy('to_be_delivered_at', 'asc')
            ->get();
    }

    private static function placeOrder($orderSubscription){
        $order = $orderSubscription->order;
        $properties = self::getOrderProperties($orderSubscription);
        $newOrderData = [
            "status" => Order::$unpaid,
            "user_id" => $order->user_id,
            "shipping_method_id" => ($order->shipping_method_id === -1) ? 1 : $order->shipping_method_id,
            "payment_method_id" => 2,
            "transaction_id" => "",
            "consignment_id" => "",
            "shipping_id" => $order->shipping_id,
            "billing_id" => $order->billing_id,
            "discount" => $properties["summary"]["discount"],
            "total" => $properties["summary"]["total"],
            "properties" => json_encode($properties)
        ];
        $newOrder = Order::create($newOrderData);
        self::addOrderProduct($newOrder, $orderSubscription->orderProduct);
        return $newOrder;
    }

    private static function getOrderProperties($orderSubscription){
        $properties = $orderSubscription->order->properties;
        return [
            "tas" => self::getTransportServiceAgreement($orderSubscription->order),
            "summary" => self::computeSummary($orderSubscription->order, $orderSubscription->orderProduct),
            "netaxept" => false,
            "promocode" => null,
            "service_partner" => (isset($properties->service_partner)) ? $properties->service_partner : null
        ];
    }

    private static function computeSummary($order, $orderProduct){
        $amount = $orderProduct->amount;
        $product = $orderProduct->product;
        $subscription = $orderProduct->subscription;

        $goods = $product->price * $amount;      
        $cost = $product->price * $amount;
        $cost = $cost - round($cost * $subscription->discount * 0.01);
        $discount = $goods - $cost;
        
        $setting = Setting::first();

        $payment = self::getPaymentCost();
        $shipping = ($setting->configuration->free_shipping && $cost > $setting->configuration->free_shipping_amount) 
            ? 0 : self::getShippingCost($order);
        $subtotal = $cost + $shipping + $payment;

        return [
            "tax" => round($subtotal * 0.25),
            "goods" => $goods,
            "total" => $subtotal,
            "discount" => $discount,
            "shipping" => $shipping,
            "subtotal" => $subtotal
        ];

    }

    private static function getTransportServiceAgreement($order){
        $id = ($order->shipping_method_id === -1) ? 1 : $order->shipping_method_id;
        $method = ShippingMethod::find($id);
        return $method->handle; 
    }

    private static function getShippingCost($order){
        $id = ($order->shipping_method_id === -1) ? 1 : $order->shipping_method_id;
        $method = ShippingMethod::find($id);
        return $method->price; // maybe we should verufy if free shipping is on.
    }

    private static function getPaymentCost(){
        $method = PaymentMethod::find(2);
        return $method->price; 
    }

    
    private static function addOrderProduct($newOrder, $orderProduct){
        $orderProductData = array(
            "order_id" => $newOrder->id,
            "product_id" => $orderProduct->product_id,
            "subscription_id" => $orderProduct->subscription_id,
            "amount" => $orderProduct->amount
        );
        OrderProduct::create($orderProductData);
    }
    
    
    private static function createBackgroundTasks($order){
        $properties = ["order_id" => $order->id];
        Tasks::add(Task::$orderSubscriptionNotificationEmail, $properties);
        Tasks::add(Task::$automateCargonizer, $properties);
    }

}