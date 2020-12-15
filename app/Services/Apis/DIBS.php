<?php

namespace App\Services\Apis;

use App\Services\Orders\Manager;
use App\Models\Orders\Order;

Class DIBS {
    public static function createCheckout($checkout) {

        $order_line = [];
        $order_tax_amount = 0;
        $order_amount = 0;
        // $order = Manager::createDIBSOrder($checkout);
        // return $order;
        foreach($checkout["products"] as $value) {
            $subsciption = 0;
            if ($value["subscription_id"] == "2") {
                $subsciption = 0.05;
            }
            elseif ($value["subscription_id"] == "3") {
                $subsciption = 0.1;
            }
            $order_line[] = [
                "reference" => $value["id"],
                "name" => $value["name"],
                "quantity" => $value["total"],
                "unit" => "pcs",
                "unitPrice" => ceil($value["price"] * (1 - $subsciption)) * (100 -  $checkout["summary"]["discount_value"]),
                "taxRate" => 2500,
                "taxAmount" => ceil($value["price"] * (1 - $subsciption) * $value["total"] * (100 -  $checkout["summary"]["discount_value"]) / 125 * 25),
                "grossTotalAmount" => ceil($value["price"] * (1 - $subsciption)) * (100 -  $checkout["summary"]["discount_value"]) * $value["total"],
                "netTotalAmount" => ceil($value["price"] * (1 - $subsciption) * (100 -  $checkout["summary"]["discount_value"])) * $value["total"] 
                        - ceil($value["price"] * (1 - $subsciption) * $value["total"] * (100 -  $checkout["summary"]["discount_value"]) / 125 * 25)
            ];
            $order_amount += ceil($value["price"] * (1 - $subsciption)) * (100 -  $checkout["summary"]["discount_value"]) * $value["total"];
        }

        $order_line[] = [
            "reference" => $checkout["shipping_method_id"],
            "name" => $checkout["shipping_method_name"],
            "quantity" => 1.0,
            "unit" => "NA",
            "unitPrice" => $checkout["summary"]["shipping"] * 100,
            "taxRate" => 2500,
            "taxAmount" => $checkout["summary"]["shipping"] * 100 / 125 * 25,
            "grossTotalAmount" => $checkout["summary"]["shipping"] * 100,
            "netTotalAmount" => $checkout["summary"]["shipping"] * 100 / 125 * 100
        ];

        $order_amount += $checkout["summary"]["shipping"] * 100;

        $datastring = [
            "order" => [
                "items" => $order_line,
                "amount" => $order_amount,
                "currency" => "NOK",
                "reference" => "Demo Order"
            ],
            "checkout" => [
                "url" => getenv('MERCHANT_URL') . "/handlekurv",
                "termsUrl" => getenv('MERCHANT_URL') . "/salgs-og-leveringsbetingelser",
                "shipping" => [
                    "countries" => [],
                    "costSpecified" => true
                ],
                "consumerType" => [
                    "supportedTypes" => [ "B2C", "B2B" ],
                    "default" => "B2C"
                ]
            ]
        ];
        if ($checkout["isSubscriptionEnabled"] == "true") {
            $datastring["checkout"]["charge"] = false;
            $date = new \DateTime();
            $format = "Y-m-d H:i:s";
            $datastring["subscription"]["endDate"] = $date->modify('+12 month')->format($format);
            $datastring["subscription"]["interval"] = 0;
        }
        $datastring = json_encode($datastring);
        $ch = curl_init(getenv('DIBS_URL') . '/payments');                                                                     
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $datastring);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: ' . getenv('SECRET_KEY'))
        );                                                                                                               
        $result = curl_exec($ch);
        return $result;
    }

    public static function initializedCheckout($payId, $order) {
        $ch=curl_init(getenv('DIBS_URL') . '/payments/' . $payId . '');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array( 
            'Content-Type: application/json', 
            'Accept: application/json', 
            'Authorization: ' . getenv('SECRET_KEY')));

        $dibs=json_decode(curl_exec($ch), true);

        $order["addresses"]["billing"]["first_name"] = $dibs["payment"]["consumer"]["privatePerson"]["firstName"];
        $order["addresses"]["billing"]["last_name"] = $dibs["payment"]["consumer"]["privatePerson"]["lastName"];
        $order["addresses"]["billing"]["email"] = $dibs["payment"]["consumer"]["privatePerson"]["email"];
        $order["addresses"]["billing"]["phone"] = $dibs["payment"]["consumer"]["privatePerson"]["phoneNumber"]["prefix"] + $dibs["payment"]["consumer"]["privatePerson"]["phoneNumber"]["number"];
        $order["addresses"]["billing"]["street_address"] = $dibs["payment"]["consumer"]["shippingAddress"]["addressLine1"];
        $order["addresses"]["billing"]["postal_code"] = $dibs["payment"]["consumer"]["shippingAddress"]["postalCode"];
        $order["addresses"]["billing"]["city"] = $dibs["payment"]["consumer"]["shippingAddress"]["city"];

        $order["addresses"]["shipping"]["first_name"] = $dibs["payment"]["consumer"]["privatePerson"]["firstName"];
        $order["addresses"]["shipping"]["last_name"] = $dibs["payment"]["consumer"]["privatePerson"]["lastName"];
        $order["addresses"]["shipping"]["email"] = $dibs["payment"]["consumer"]["privatePerson"]["email"];
        $order["addresses"]["shipping"]["phone"] = $dibs["payment"]["consumer"]["privatePerson"]["phoneNumber"]["prefix"] + $dibs["payment"]["consumer"]["privatePerson"]["phoneNumber"]["number"];
        $order["addresses"]["shipping"]["street_address"] = $dibs["payment"]["consumer"]["shippingAddress"]["addressLine1"];
        $order["addresses"]["shipping"]["postal_code"] = $dibs["payment"]["consumer"]["shippingAddress"]["postalCode"];
        $order["addresses"]["shipping"]["city"] = $dibs["payment"]["consumer"]["shippingAddress"]["city"];

        $order["payment_method_id"] = 3;
        $order["dibs_order"] = $payId;

        $merchant_order = Manager::createDIBSOrder($order, $dibs);

        $ch=curl_init(getenv('DIBS_URL') . '/payments/' . $payId . '/referenceinformation');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array( 
            'Content-Type: application/json', 
            'Accept: application/json', 
            'Authorization: ' . getenv('SECRET_KEY')));

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(["reference" => $merchant_order->id, "checkoutUrl" => getenv('MERCHANT_URL') . "/handlekurv"]));
        $dibs=curl_exec($ch);
        return $dibs;
    }

    public static function subscription() {
        $ch=curl_init('https://test.api.dibspayment.eu/v1/subscriptions/a429b4acf11843c39cecacdb99ddb99f');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array( 
            'Content-Type: application/json', 
            'Accept: application/json', 
            'Authorization: ' . getenv('SECRET_KEY')));

        $dibs = json_decode(curl_exec($ch), true);
        return $dibs;
    }

    public static function chargeSubscription($orderId) {
        $ch=curl_init(getenv('DIBS_URL') . '/subscriptions/charges');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array( 
            'Content-Type: application/json', 
            'Accept: application/json', 
            'Authorization: ' . getenv('SECRET_KEY')));
        
        $externalBulkChargeId = uniqid();
        $order = Order::with(["shipping", "shipping_method", "user", "products.product", "products.subscription", "products.product.images"])
        ->find($orderId);

        $order_line = [];
        $order_tax_amount = 0;
        $order_amount = 0;
        // $order = Manager::createDIBSOrder($checkout);
        // return $order;
        foreach($order["products"] as $value) {
            $subsciption = 0;
            if ($value["subscription_id"] == "2") {
                $subsciption = 0.05;
            }
            elseif ($value["subscription_id"] == "3") {
                $subsciption = 0.1;
            }
            $order_line[] = [
                "reference" => $value["product"]["id"],
                "name" => $value["product"]["name"],
                "quantity" => $value["amount"],
                "unit" => "pcs",
                "unitPrice" => ceil($value["product"]["price"] * (1 - $subsciption)) * (100 -  $order->properties->summary->discount_value),
                "taxRate" => 2500,
                "taxAmount" => ceil($value["product"]["price"] * (1 - $subsciption) * $value["amount"] * (100 -  $order->properties->summary->discount_value) / 125 * 25),
                "grossTotalAmount" => ceil($value["product"]["price"] * (1 - $subsciption)) * (100 -  $order->properties->summary->discount_value) * $value["amount"],
                "netTotalAmount" => ceil($value["product"]["price"] * (1 - $subsciption) * (100 -  $order->properties->summary->discount_value)) * $value["amount"] 
                        - ceil($value["product"]["price"] * (1 - $subsciption) * $value["amount"] * (100 -  $order->properties->summary->discount_value) / 125 * 25)
            ];
            $order_amount += ceil($value["product"]["price"] * (1 - $subsciption)) * (100 -  $order->properties->summary->discount_value) * $value["amount"];
        }

        $order_line[] = [
            "reference" => $order["shipping_method_id"],
            "name" => $order["shipping_method"]["name"],
            "quantity" => 1.0,
            "unit" => "NA",
            "unitPrice" => $order["shipping_method"]["price"] * 100,
            "taxRate" => 2500,
            "taxAmount" => $order["shipping_method"]["price"] * 100 / 125 * 25,
            "grossTotalAmount" => $order["shipping_method"]["price"] * 100,
            "netTotalAmount" => $order["shipping_method"]["price"] * 100 / 125 * 100
        ];

        $order_amount += $order["shipping_method"]["price"] * 100;
        $datastring = [
            "externalBulkChargeId" => $externalBulkChargeId,
            "notifications" => null,
            "subscriptions" => [[
                "subscriptionId" => $order->properties->payment->subscription->id,
                "order" => [
                    "items" => $order_line,
                    "amount" => $order_amount,
                    "currency" => "NOK",
                    "reference" => $order->id
                    ]
                ]]
            ];

        // return $order->properties->payment->subscription->id;
        // return $datastring;
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datastring, true));

        $dibs = json_decode(curl_exec($ch), true);

        $extended = (object) array_merge((array) $order->properties, (array) $dibs);
        $order->properties = json_encode($extended);
        $order->status = Order::$paid;
        $order->save();

        return $dibs;
    }
}
?>