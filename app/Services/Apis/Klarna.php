<?php

namespace App\Services\Apis;

use Klarna\Rest\Transport\Connector;
use Klarna\Rest\Transport\ConnectorInterface;
use Klarna\Rest\Checkout\Order;
use Klarna\Rest\OrderManagement\Order as OrderInStore;
use App\Http\Services\Orders\Manager;

Class Klarna {

    public static function createCheckout($checkout) {
        $merchantId = getenv('MERCHANT_ID') ?: 'PK04018_b047100e3862';
        $sharedSecret = getenv('SHARED_SECRET') ?: 'AwmgVW1bHVJ1ebYo';
        
        $connector = Connector::create(
            $merchantId,
            $sharedSecret,
            getenv('TEST_ENVIRONMENT') === 'true' ? 
                ConnectorInterface::EU_TEST_BASE_URL :
                ConnectorInterface::EU_BASE_URL
        );
        $order_line = [];
        $order_tax_amount = 0;
        $order_amount = 0;
        foreach($checkout["products"] as $value) {
            $order_line[] = [
                "type" => "physical",
                "reference" => $value["id"],
                "name" => $value["name"],
                "quantity" => $value["total"],
                "quantity_unit" => "pcs",
                "unit_price" => $value["price"] * 100,
                "tax_rate" => 2500,
                "total_amount" => $value["price"] * $value["total"] * (100 -  $checkout["summary"]["discount_value"]),
                "total_tax_amount" => $value["price"] * $value["total"] * (100 -  $checkout["summary"]["discount_value"]) / 125 * 25,
                "total_discount_amount" => $value["price"] * $value["total"] * $checkout["summary"]["discount_value"],
                "image_url" => $value["image"],
                "product_url" => getenv('MERCHANT_URL') . '/produkt/' . $value["handle"]
            ];
            $order_amount += $value["price"] * $value["total"] * (100 -  $checkout["summary"]["discount_value"]);
            $order_tax_amount += $value["price"] * $value["total"] * (100 -  $checkout["summary"]["discount_value"]) / 125 * 25;
        }
        $order = [
            "purchase_country" => "no",
            "purchase_currency" => "nok",
            "locale" => "nb-no",
            "order_amount" => $order_amount,
            "order_tax_amount" => $order_tax_amount,
            "order_lines" => $order_line,
            "merchant_urls" => [
              "terms" => "http://merchant.com/tac.php",
              "checkout" => getenv('MERCHANT_URL') . "/handlekurv?sid={checkout.order.id}",
              "confirmation" => getenv('MERCHANT_URL') . "/takk?sid={checkout.order.id}",
              "push" => getenv('MERCHANT_URL') . "/api/klarna/push?checkout_uri={checkout.order.id}"
            ],
            "options" => [
                "shipping_countries" => [
                    "no"
                ],
                "radius_border" => "5px",
                "allow_separate_shipping_address" => true,
                "national_identification_number_mandatory" => false,
                "allowed_customer_types" => ["person", "organization"],
            ],
            "shipping_options" => [
                [
                  "id" => $checkout["shipping_method_id"],
                  "name" => $checkout["shipping_method_name"],
                  "price" => $checkout["summary"]["shipping"] * 100,
                  "tax_amount" => $checkout["summary"]["shipping"] * 100 / 125 * 25,
                  "tax_rate" => 2500,
                  "description" => $checkout["shipping_method_id"] == 6 ? $checkout["service_partner"]["deliveryAddress"]["streetName"] . ' ' . ($checkout["service_partner"]["deliveryAddress"]["streetNumber"] ? $checkout["service_partner"]["deliveryAddress"]["streetNumber"] : '')
                   . ' ' . $checkout["service_partner"]["deliveryAddress"]["city"] . ', '. $checkout["service_partner"]["deliveryAddress"]["postalCode"] : "Postnord frakt",
                  "preselected" => true
                ]
            ],
            "attachment" => [
                "content_type" => "application/vnd.klarna.internal.emd-v2+json",
                "body" => json_encode($checkout)
            ]
        ];

        // return $order;
        try {
            $checkout = new Order($connector);
            $checkout->create($order);
        
            return $checkout;
            } catch (Exception $e) {
                return $e->getMessage();
            }
        }

    public static function fetchCheckout($orderId) {
        $merchantId = getenv('MERCHANT_ID') ?: 'PK04018_b047100e3862';
        $sharedSecret = getenv('SHARED_SECRET') ?: 'AwmgVW1bHVJ1ebYo';

        $connector = Connector::create(
            $merchantId,
            $sharedSecret,
            getenv('TEST_ENVIRONMENT') === 'true' ? 
                ConnectorInterface::EU_TEST_BASE_URL :
                ConnectorInterface::EU_BASE_URL
        );

        try {
            $checkout = new Order($connector, $orderId);
            $checkout->fetch();

            return $checkout;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public static function updateCheckout($orderId, $checkout) {
        $merchantId = getenv('MERCHANT_ID') ?: 'PK04018_b047100e3862';
        $sharedSecret = getenv('SHARED_SECRET') ?: 'AwmgVW1bHVJ1ebYo';
        
        $connector = Connector::create(
            $merchantId,
            $sharedSecret,
            getenv('TEST_ENVIRONMENT') === 'true' ? 
                ConnectorInterface::EU_TEST_BASE_URL :
                ConnectorInterface::EU_BASE_URL
        );
        $order_line = [];
        $order_tax_amount = 0;
        $order_amount = 0;
        foreach($checkout["products"] as $value) {
            $order_line[] = [
                "type" => "physical",
                "reference" => $value["id"],
                "name" => $value["name"],
                "quantity" => $value["total"],
                "quantity_unit" => "pcs",
                "unit_price" => $value["price"] * 100,
                "tax_rate" => 2500,
                "total_amount" => $value["price"] * $value["total"] * (100 -  $checkout["summary"]["discount_value"]),
                "total_tax_amount" => $value["price"] * $value["total"] * (100 -  $checkout["summary"]["discount_value"]) / 125 * 25,
                "total_discount_amount" => $value["price"] * $value["total"] * $checkout["summary"]["discount_value"],
                "image_url" => $value["image"],
                "product_url" => getenv('MERCHANT_URL') . '/produkt/' . $value["handle"]
            ];
            $order_amount += $value["price"] * $value["total"] * (100 -  $checkout["summary"]["discount_value"]);
            $order_tax_amount += $value["price"] * $value["total"] * (100 -  $checkout["summary"]["discount_value"]) / 125 * 25;
        }
        $order = [
            "order_amount" => $order_amount,
            "order_tax_amount" => $order_tax_amount,
            "order_lines" => $order_line,
            "shipping_options" => [
                [
                  "id" => $checkout["shipping_method_id"],
                  "name" => $checkout["shipping_method_name"],
                  "price" => $checkout["summary"]["shipping"] * 100,
                  "promo" => $checkout["promo_code"],
                  "tax_amount" => $checkout["summary"]["shipping"] * 100 / 125 * 25,
                  "tax_rate" => 2500,
                  "description" => $checkout["shipping_method_id"] == 6 ? $checkout["service_partner"]["deliveryAddress"]["streetName"] . ' ' . $checkout["service_partner"]["deliveryAddress"]["streetNumber"]
                   . ' ' . $checkout["service_partner"]["deliveryAddress"]["city"] . ', '. $checkout["service_partner"]["deliveryAddress"]["postalCode"] : "Free shipping",
                  "preselected" => true
                ]
            ],
            "attachment" => [
                "content_type" => "application/vnd.klarna.internal.emd-v2+json",
                "body" => json_encode($checkout)
            ]
        ];

        try {
            $checkout = new Order($connector, $orderId);
            $checkout->update($order);

            return $checkout;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public static function push($orderId) {
        $merchantId = getenv('MERCHANT_ID') ?: 'PK04018_b047100e3862';
        $sharedSecret = getenv('SHARED_SECRET') ?: 'AwmgVW1bHVJ1ebYo';

        $connector = Connector::create(
            $merchantId,
            $sharedSecret,
            getenv('TEST_ENVIRONMENT') === 'true' ? 
                ConnectorInterface::EU_TEST_BASE_URL :
                ConnectorInterface::EU_BASE_URL
        );

        try {
            $order = new OrderInStore($connector, $orderId);
            $order->acknowledge();

            $klarna_order = $order->getOrder();
            $order = new Order($connector, $orderId);
            $data = $order->fetch();

            $attachment = json_decode($data["attachment"]["body"], true);
            $billing_address = $data["billing_address"];
            $shipping_address = $data["shipping_address"];
            
            $attachment["klarna_order_id"] = $orderId;
            
            $attachment["addresses"]["billing"]["first_name"] = $billing_address["given_name"];
            $attachment["addresses"]["billing"]["last_name"] = $billing_address["family_name"];
            $attachment["addresses"]["billing"]["email"] = $billing_address["email"];
            $attachment["addresses"]["billing"]["phone"] = $billing_address["phone"];
            $attachment["addresses"]["billing"]["street_address"] = $billing_address["street_address"];
            $attachment["addresses"]["billing"]["postal_code"] = $billing_address["postal_code"];
            $attachment["addresses"]["billing"]["city"] = $billing_address["city"];

            $attachment["addresses"]["shipping"]["first_name"] = $shipping_address["given_name"];
            $attachment["addresses"]["shipping"]["last_name"] = $shipping_address["family_name"];
            $attachment["addresses"]["shipping"]["email"] = $shipping_address["email"];
            $attachment["addresses"]["shipping"]["phone"] = $shipping_address["phone"];
            $attachment["addresses"]["shipping"]["street_address"] = $shipping_address["street_address"];
            $attachment["addresses"]["shipping"]["postal_code"] = $shipping_address["postal_code"];
            $attachment["addresses"]["shipping"]["city"] = $shipping_address["city"];

            $attachment["payment_method_id"] = 3;
            $attachment["klarna_order"] = $klarna_order;
            return $attachment;

        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public static function createCapture($orderId, $order) {
        $merchantId = getenv('MERCHANT_ID') ?: 'PK04018_b047100e3862';
        $sharedSecret = getenv('SHARED_SECRET') ?: 'AwmgVW1bHVJ1ebYo';

        $connector = Connector::create(
            $merchantId,
            $sharedSecret,
            getenv('TEST_ENVIRONMENT') === 'true' ? 
                ConnectorInterface::EU_TEST_BASE_URL :
                ConnectorInterface::EU_BASE_URL
        );

        try {
            $order_lines = [];
            foreach($order->products as $product) {
                $order_lines[] = [
                    "type" => "physical",
                    "reference" => $product->product_id,
                    "name" => $product->product->name,
                    "quantity" => $product->amount,
                    "quantity_unit" => "item",
                    "unit_price" => $product->product->price * 100,
                    "tax_rate" => 2500,
                    "total_amount" => $product->product->price * $product->amount * 100,
                    "total_tax_amount" => $product->product->price * $product->amount * 100 / 125 * 25
                ];
            }
            $order_lines[] = [
                "type" => "shipping_fee",
                "reference" => $order->shipping_method->id,
                "name" => $order->shipping_method->name,
                "quantity" => 1,
                "quantity_unit" => "pcs",
                "unit_price" => $order->shipping_method->price * 100,
                "tax_rate" => 2500,
                "total_amount" => $order->shipping_method->price * 100,
                "total_tax_amount" => $order->shipping_method->price * 100 / 125 * 25
            ];
            if ($order->properties->tas === "mypack") {
                $shipping_method = "PickUpPoint";
            } else if ($order->properties->tas === "tg_home_delivery") {
                $shipping_method = "Home";
            } else if ($order->properties->tas === "tg_stykkgods") {
                $shipping_method = "BoxReg";
            }
            $klarnaOrder = new OrderInStore($connector, $orderId);
            $klarnaOrder->createCapture([
                "captured_amount" => $order->total * 100,
                "description" => "Shipped part of the order",
                "order_lines" => $order_lines,
                "shipping_info" => [
                    [
                        "shipping_company" => "PostNord",
                        "shipping_method" => $shipping_method,
                        "tracking_uri" => $order->properties->consignment->{'tracking-url'},
                        // "return_tracking_number" => "E-55-KL",
                        // "return_shipping_company" => "PostNord",
                        // "return_tracking_uri" => "http://www.dhl.com/content/g0/en/express/tracking.shtml?brand=DHL&AWB=98389222"
                    ]
                ]
            ]);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public static function updateMerchatReference($orderId, $merchantOrderId) {
        $merchantId = getenv('MERCHANT_ID') ?: 'PK04018_b047100e3862';
        $sharedSecret = getenv('SHARED_SECRET') ?: 'AwmgVW1bHVJ1ebYo';

        $connector = Connector::create(
            $merchantId,
            $sharedSecret,
            getenv('TEST_ENVIRONMENT') === 'true' ? 
                ConnectorInterface::EU_TEST_BASE_URL :
                ConnectorInterface::EU_BASE_URL
        );
        try {
            $klarnaOrder = new OrderInStore($connector, $orderId);
            $klarnaOrder->updateMerchantReferences([
                "merchant_reference1" => $merchantOrderId
            ]);
            return 'Merchant references have been updated.';
        } catch (Exception $e) {
            return 'Caught exception: ' . $e->getMessage() . "\n";
        }
    }
}
?>