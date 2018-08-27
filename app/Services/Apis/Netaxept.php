<?php

namespace App\Services\Apis;

use App\Models\Orders\Order;
use App\Models\Core\Task;

use App\Services\Core\Tasks;

//https://shop.nets.eu/nb/web/partners/appi
class Netaxept extends ApiClient{
    
    /*private static $merchantId = "12002777";
    private static $token = "6Fb)q3!";
    private static $baseUrl = "https://test.epayment.nets.eu/Netaxept/";
    private static $terminalUrl = "https://test.epayment.nets.eu/terminal/";*/

    private static $merchantId = "651641";
    private static $token = "Qs8(g6?"; 
    private static $baseUrl = "https://epayment.nets.eu/Netaxept/";
    private static $terminalUrl = "https://epayment.nets.eu/terminal/";

    public static function automate($oid){
        $order = Order::find($oid);
        if($order !== null){
            $response = new \stdClass();
            $response->authorisation = self::auth($order->transaction_id);
            //$response->capture = self::capture($order->transaction_id, $order->total);
            $extended = (object) array_merge((array) $order->properties, (array) $response);
            $order->properties = json_encode($extended);
            
            if(!isset($response->authorisation["Error"])){
                $order->status = Order::$paid;
                $properties = ["order_id" => $order->id];
                if(!$order->properties->guest){
                    Tasks::add(Task::$orderConfirmationEmail, $properties);
                }
                Tasks::add(Task::$newOrderNotificationEmail, $properties);
                Tasks::add(Task::$automateCargonizer, $properties);
            }else{
                $order->status = Order::$paymentFailed;
            }
            $order->save();
            }
        return $order;
    }

    public static function register($orderNumber, $amount){
        $url = self::$baseUrl . "Register.aspx";
        return ($orderNumber != null && $amount != null) ? self::parseXMLString(
            self::get($url, [
                "query" => [
                    "merchantId" => self::$merchantId,
                    "token" => self::$token,
                    "orderNumber" => $orderNumber,
                    "amount" => sprintf("%d", $amount * 100),
                    "CurrencyCode" => "NOK",
                    "redirectUrl" => route("payment")
                ]
            ])
        ) : ["error" => "Neither orderNumber nor amount must be null"];
        /*return self::parseXMLString('<RegisterResponse xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
        <TransactionId>6c5e402a4ee445ba9f19411f55c75b91</TransactionId>
        </RegisterResponse>');*/
    }

    public static function terminal($transactionId, $isMobile = false){
        $url = ($isMobile) ? self::$terminalUrl . "mobile/default.aspx" : self::$terminalUrl . "default.aspx";
        return $url . "?merchantId=" . self::$merchantId . "&transactionId=" . $transactionId;
    }

    public static function auth($transactionId){
        $url = self::$baseUrl . "Process.aspx";
        return ($transactionId != null) ? self::parseXMLString(
            self::get($url, [
                "query" => [
                    "merchantId" => self::$merchantId,
                    "token" => self::$token,
                    "transactionId" => $transactionId,
                    "operation" => "AUTH"
                ]
            ])
        ) : ["error" => "transactionId must not be null"];
    }

    public static function capture($transactionId, $transactionAmount){
        $url = self::$baseUrl . "Process.aspx";
        return ($transactionId != null && $transactionAmount != null) ? self::parseXMLString(
            self::get($url, [
                "query" => [
                    "merchantId" => self::$merchantId,
                    "token" => self::$token,
                    "transactionId" => $transactionId,
                    "transactionAmount" => sprintf("%d", $transactionAmount * 100),
                    "operation" => "CAPTURE"
                ]
            ])
        ) : ["error" => "Neither transactionId nor transactionAmount must be null"];
    }

    public static function query($transactionId){
        $url = self::$baseUrl . "Query.aspx";
        return ($transactionId != null) ? self::parseXMLString(
            self::get($url, [
                "query" => [
                    "merchantId" => self::$merchantId,
                    "token" => self::$token,
                    "transactionId" => $transactionId
                ]
            ])
        ) : ["error" => "transactionId must not be null"];
    }
}