<?php

namespace App\Services\Apis;

//https://logistra.no/api-documentation.html
class CargonizerAbstract extends ApiClient
{

    /*private static $key = "656e1a09255332e6262d31c4767ef3878a12bdef";
    private static $senderId = "1241";
    private static $transportAgreementId = "1238";
    protected static $baseUrl = "http://sandbox.cargonizer.no/";*/

    private static $key = "4e39ee8576983d2ac345c58d8d07bdbdf93241cc";
    private static $senderId = "4642";
    private static $transportAgreementId = "11043";
    private static $bringTransportAgreementId = "9730";
    protected static $baseUrl = "https://cargonizer.no/";
    protected static $bringAPIUrl = "https://api.bring.com/pickuppoint/api/pickuppoint/NO/postalCode/";

    private static $transportAgreement = [ // standard Pakker (ny)Bring
        "id" => "9730",
        "number" => "10033826024",
        "products" => [
            "helthjem_mypack" => "hent i butikk",
            "helthjem_parcel_letter_mypack" => "helthjem m/hent i butikk",
            "helthjem_mypack_return" => "retur hent i butikk",
            "varebrev_split" => "helthjem samlesending",
            "helthjem_dpd_domestic" => "bedriftspakke",
            "helthjem_home_delivery" => "personlig hjemlevering",
        ],
    ];

    protected static function getRequestHeaders()
    {
        return array(
            "X-Cargonizer-Key" => self::$key,
            "X-Cargonizer-Sender" => self::$senderId,
        );
    }

    protected static function getPostRequestHeaders()
    {
        return array(
            "X-Cargonizer-Key" => self::$key,
            "X-Cargonizer-Sender" => self::$senderId,
            "Content-Type" => "application/xml",
        );
    }

    protected static function getReturnConsignmentXMLDescription($order, $transfer = false, $bookingRequest = false)
    {
        $return_service = '';
        $xml = '<consignments>
            <consignment transport_agreement="' . self::$transportAgreementId . '">
            <values>' . self::getValues() . '</values>';
        if ($transfer) {
            $xml .= "<transfer>true</transfer>";
        }
        if ($bookingRequest) {
            $xml .= "<booking-request>true</booking-request>";
        }
        if ($order->properties->tas === "bring_bedr_dor_dor") {
            $return_service = 'bring_bedriftspakke_return';
        } else if ($order->properties->tas === "bring_servicepakke") {
            $return_service = 'bring_servicepakke_return';
        } else if ($order->properties->tas === "bring_pa_doren") {
            $return_service = 'bring_home_delivery_return';
        }
        $xml .= '<product>' . $return_service . '</product>
            <parts>' . self::getParts($order) . '</parts>
            <items>' . self::getItemsDescription($order->products) . '</items>
            <references>
                <consignor>' . $order->identifier . '</consignor>
                <consignee>' . $order->identifier . '</consignee>
            </references>
            <services>' . self::getServicesForTAS($order->properties->tas) . ' </services>
            <messages></messages>
            </consignment>
        </consignments>';
        return str_replace(PHP_EOL, '', $xml);
    }

    protected static function getConsignmentXMLDescription($order, $transfer = false, $bookingRequest = false)
    {
        // $transportAgreementId = strpos($order->properties->tas, 'bring') !== false ? self::$bringTransportAgreementId : self::$transportAgreementId;
        $tas = $order->properties->tas;
        if (strcmp($order->properties->tas, 'bring_bedr_dor_dor') == 0) {
            $tas = 'tg_stykkgods';
        }
        if (strcmp($order->properties->tas, 'bring_pa_doren') == 0) {
            $tas = 'tg_home_delivery';
        }
        if (strcmp($order->properties->tas, 'bring_servicepakke') == 0) {
            $tas = 'mypack';
        }
        $xml = '<consignments>
            <consignment transport_agreement="' . self::$transportAgreementId . '">
            <values>' . self::getValues() . '</values>';
        if ($transfer) {
            $xml .= "<transfer>true</transfer>";
        }
        if ($bookingRequest) {
            $xml .= "<booking-request>true</booking-request>";
        }
        $xml .= '<product>' . $tas . '</product>
            <parts>' . self::getParts($order) . '</parts>
            <items>' . self::getItemsDescription($order->products) . '</items>
            <references>
                <consignor>' . $order->identifier . '</consignor>
                <consignee>' . $order->identifier . '</consignee>
            </references>
            <services>' . self::getServicesForTAS($tas) . ' </services>
            <messages></messages>
            </consignment>
        </consignments>';
        return str_replace(PHP_EOL, '', $xml);
    }

    private static function getServicesForTAS($tas)
    {
        // if (strpos($tas, 'bring') !== false) {
        //     return ($tas === 'bring_servicepakke') ? '<service id="bring_e_varsle_for_utlevering"/>' : '';
        // } else {
        $xml = '<service id="postnord_notification_email"/>';
        $xml .= ($tas === 'mypack') ? '<service id="postnord_notification_sms"/>' : '';
        return ($tas === 'mypack' || $tas === 'tg_home_delivery') ? $xml : '';
        // }
    }

    private static function getItemsDescription($products)
    {
        $xml = '<item type="package" amount="1" weight="2" description="';
        $cnt = 0;
        foreach ($products as $product) {
            if ($cnt > 0) {
                $xml .= ' | ' . $product->amount . ' stk , ' . $product->product->name . ' , ' . $product->product->sku;
            } else {
                $xml .= $product->amount . ' stk , ' . $product->product->name . ' , ' . $product->product->sku;
            }
            $cnt++;
        }
        $xml .= '" />';
        return $xml;
    }

    private static function getValues()
    {
        $xml = '<value name="provider" value="Barefilter" />
        <value name="provider-email" value="kjell@erslandklima.no" />';
        /*<value name="order" value="123" />
        <value name="humbaba" value="enkidu" />';*/
        return $xml;
    }

    private static function getParts($order)
    {
        $xml = self::getConsignee($order->shipping);
        $xml .= self::getServicePartner($order);
        $xml .= self::getReturnAddress();
        return $xml;
    }

    private static function getConsignee($shipping)
    {
        $xml = '<consignee>
        <name>' . $shipping->first_name . ' ' . $shipping->last_name . '</name>
        <postcode>' . $shipping->postal_code . '</postcode>
        <address1>' . $shipping->street_address . '</address1>
        <city>' . $shipping->city . '</city>
        <country>NO</country>
        <mobile>' . $shipping->phone . '</mobile>
        <contact-person>' . $shipping->first_name . '</contact-person>
        <email>' . $shipping->email . '</email>
        </consignee>';
        return $xml;
    }

    private static function getServicePartner($order)
    {
        $xml = '';
        if ($order->properties->service_partner != null) {
            $pickup = $order->properties->service_partner;
            if (isset($pickup->unitId)) {
                $xml = '<service-partner>
                <number>' . $pickup->unitId . '</number>
                <name>' . $pickup->name . '</name>
                <address1>' . $pickup->address . '</address1>
                <postcode>' . $pickup->postalCode . '</postcode>
                <city>' . $pickup->city . '</city>
                <country>' . $pickup->countryCode . '</country>
                </service-partner>';
            } else {
                $xml = '<service-partner>
                <number>' . $pickup->number . '</number>
                <name>' . $pickup->name . '</name>
                <address1>' . $pickup->address1 . '</address1>
                <postcode>' . $pickup->postcode . '</postcode>
                <city>' . $pickup->city . '</city>
                <country>' . $pickup->country . '</country>
                </service-partner>';
            }
        }
        return $xml;
    }

    private static function getReturnAddress()
    {
        $xml = '<return_address>
        <name>Bare Filter AS.</name>
        <contact-person>Kjell </contact-person>
        <address1>Valevegen 22</address1>
        <postcode>5451</postcode>
        <city>Valen</city>
        <country>NO</country>
        </return_address>';
        return $xml;
    }

    protected static function getQueryString($consignmentIds, $pieceIds = null)
    {
        $consignments = explode(",", $consignmentIds);
        $array = array();
        foreach ($consignments as $consignmentId) {
            array_push($array, "consignment_ids[]=" . $consignmentId);
        }
        if ($pieceIds != null) {
            $pieces = explode(",", $pieceIds);
            foreach ($pieces as $pieceId) {
                array_push($array, "piece_ids[]=" . $pieceId);
            }
        }
        $query = join('&', $array);
        return $query;
    }
}
