<?php

namespace App\Services\Apis;

use App\Models\Orders\Order;

//https://logistra.no/api-documentation.html
class Cargonizer extends CargonizerAbstract
{

    public static function automate($oid)
    {
        $order = Order::where('id', $oid)
            ->with(["products.product", "shipping"])
            ->get()[0];
        if($order !== null){
            $consignments = self::createConsignment($order);
            $response = new \stdClass();
            $response->consignment = $consignments["consignment"];
            $extended = (object) array_merge((array) $order->properties, (array) $response);
            $order->properties = json_encode($extended);
            $order->status = (!isset($response->consignment["errors"])) ? Order::$readyForShipping : Order::$shippingError;
            $order->save();
        }
        return $order;
    }
    
    public static function createConsignment($order)
    {
        $consignment = self::getConsignmentXMLDescription($order, true);
        return ($consignment != null) ? self::parseXMLString(
            self::post(
                self::$baseUrl . "consignments.xml", [
                    "body" => $consignment,
                    "headers" => self::getPostRequestHeaders()
                ]
            )
        ) : ["error" => ""];
    }

    public static function getTransportAgreement()
    {
        $parameters = array(
            "headers" => self::getRequestHeaders()
        );
        return self::parseXMLString(
            self::get(self::$baseUrl . "transport_agreements.xml", $parameters)
        );
    }

    public static function getPickupPoints($postcode)
    {
        $parameters = array(
            "headers" => self::getRequestHeaders(),
            "query" => [
               "country" => "NO",
               "postcode" => $postcode,
               "carrier" => "helthjem"
            ]
        );
        $response = self::parseXMLString(
            self::get(self::$baseUrl . "service_partners.xml", $parameters)
        );
        return ($response["errors"] === null) ? [
            "partners" => $response["service-partners"]["service-partner"],
            "location" => $response["location"]
        ] : ["error" => $response["errors"]];
    }

    public static function estimateShippingCost($order)
    {
        $consignment = self::getConsignmentXMLDescription($order);
        return ($consignment != null) ? self::parseXMLString(
            self::post(
                self::$baseUrl . "consignment_costs.xml", [
                    "body" => $consignment,
                    "headers" => self::getPostRequestHeaders()
                ]
            )
        ) : ["error" => ""];
    }


    public static function getPrinters()
    {
        return self::parseXMLString(
            self::get(
                self::$baseUrl . "printers.xml",
                ["headers" => self::getRequestHeaders()]
            )
        );
    }

    public static function getLabelsAsPDF($consignmentIds, $pieceIds = null)
    {
        return ($consignmentIds != null) ? self::parseXMLString(
            self::get(
                self::$baseUrl . "consignments/label_pdf?" . self::getQueryString($consignmentIds, $pieceIds),
                ["headers" => self::getRequestHeaders()]
            )
        ): ["error" => ""];
    }

    public static function getWaybillAsPDF($consignmentIds)
    {
        return ($consignmentIds != null) ? self::parseXMLString(
            self::get(
                self::$baseUrl . "consignments/waybill_pdf?" . self::getQueryString($consignmentIds),
                ["headers" => self::getRequestHeaders()]
            )
        ): ["error" => ""];
    }

    public static function getGoodsDeclarationAsPDF($consignmentIds)
    {
        return ($consignmentIds != null) ? self::parseXMLString(
            self::get(
                self::$baseUrl . "consignments/goods_declaration_pdf?" . self::getQueryString($consignmentIds),
                ["headers" => self::getRequestHeaders()]
            )
        ): ["error" => ""];
    }
}
