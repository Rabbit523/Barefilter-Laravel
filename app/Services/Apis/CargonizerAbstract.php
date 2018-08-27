<?php

namespace App\Services\Apis;

//https://logistra.no/api-documentation.html
class CargonizerAbstract extends ApiClient{
    
    /*private static $key = "656e1a09255332e6262d31c4767ef3878a12bdef";
    private static $senderId = "1241";
    private static $transportAgreementId = "1238";
    protected static $baseUrl = "http://sandbox.cargonizer.no/";*/

    private static $key = "4e39ee8576983d2ac345c58d8d07bdbdf93241cc";
    private static $senderId = "4642";
    private static $transportAgreementId = "7693"; 
    protected static $baseUrl = "http://cargonizer.no/";
    

    private static $transportAgreement = [ // standard helthjem
        "id" => "7693",
        "number" => "3800141",
        "products" => [
            "helthjem_mypack" => "hent i butikk", 
            "helthjem_parcel_letter_mypack" => "helthjem m/hent i butikk", 
            "helthjem_mypack_return" => "retur hent i butikk", 
            "varebrev_split" => "helthjem samlesending", 
            "helthjem_dpd_domestic" => "bedriftspakke", 
            "helthjem_home_delivery" => "personlig hjemlevering"
        ]
    ];
    
    protected static function getRequestHeaders(){
        return array(
            "X-Cargonizer-Key" => self::$key,
            "X-Cargonizer-Sender" => self::$senderId
        );
    }

    protected static function getPostRequestHeaders(){
        return array(
            "X-Cargonizer-Key" => self::$key,
            "X-Cargonizer-Sender" => self::$senderId,
            "Content-Type" => "application/xml"
        );
    }

    protected static function getConsignmentXMLDescription($order, $transfer = false, $bookingRequest = false){
        $xml = '<consignments>
            <consignment transport_agreement="' . self::$transportAgreementId . '">
            <values>' . self::getValues() . '</values>';
            if($transfer){
                $xml .= "<transfer>true</transfer>";
            } 
            if($bookingRequest){
                $xml .= "<booking-request>true</booking-request>";
            }
            $xml .= '<product>' . $order->properties->tas . '</product>
            <parts>' . self::getParts($order) . '</parts>
            <items>' . self::getItemsDescription($order->products) . '</items>
            <references>
                <consignor>'. $order->identifier . '</consignor>
                <consignee>' . $order->identifier . '</consignee>
            </references>
            <services>' . self::getServicesForTAS($order->properties->tas). ' </services>
            <messages></messages>
            </consignment>
        </consignments>';
        return str_replace(PHP_EOL, '', $xml);
    }

    private static function getServicesForTAS($tas){
        $xml = '<service id="postnord_notification_email"/>';
        $xml .= ($tas === 'helthjem_mypack') ? '<service id="postnord_notification_sms"/>' : '';
        return ($tas === 'helthjem_mypack' || $tas === 'helthjem_home_delivery') ? $xml : '';
    }

    private static function getItemsDescription($products){
        $xml = '';
        foreach($products as $product){
            $xml .= '<item type="package" amount="' . $product->amount . '" weight="' . $product->amount * $product->product->weight . '" description="' . $product->product->name .' | ' . $product->product->sku .'" />';
        }
        return $xml;
    }

    private static function getValues(){
        $xml = '<value name="provider" value="Barefilter" />
        <value name="provider-email" value="kjell@erslandklima.no" />';
        /*<value name="order" value="123" />
        <value name="humbaba" value="enkidu" />';*/
        return $xml;
    }

    private static function getParts($order){
        $xml = self::getConsignee($order->shipping);
        $xml .= self::getServicePartner($order);
        $xml .= self::getReturnAddress();
        return $xml;
    }

    private static function getConsignee($shipping){
        $xml = '<consignee>
        <name>' . $shipping->first_name. ' ' . $shipping->last_name . '</name>
        <postcode>' . $shipping->postal_code . '</postcode>
        <address1>' . $shipping->street_address . '</address1>
        <city>' . $shipping->city . '</city>
        <country>NO</country>
        <mobile>' . $shipping->phone . '</mobile>
        <contact-person>' . $shipping->first_name . '</contact-person>
        <email>'. $shipping->email .'</email>
        </consignee>';
        return $xml;
    }

    private static function getServicePartner($order){
        $xml = '';
        if($order->properties->service_partner != null){
            $pickup = $order->properties->service_partner;
            $xml = '<service-partner>
            <number>' . $pickup->number . '</number>
            <name>' . $pickup->name . '</name>
            <address1>' . $pickup->address1 . '</address1>
            <postcode>' . $pickup->postcode . '</postcode>
            <city>' . $pickup->city . '</city>
            <country>' . $pickup->country . '</country>
            </service-partner>';
        }
        return $xml;
    }

    
    private static function getReturnAddress(){
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

    protected static function getQueryString($consignmentIds, $pieceIds = null){
        $consignments = explode(",", $consignmentIds);
        $array = array();
        foreach($consignments as $consignmentId){
            array_push($array, "consignment_ids[]=" . $consignmentId);
        }
        if($pieceIds != null){
            $pieces = explode(",", $pieceIds);
            foreach($pieces as $pieceId){
                array_push($array, "piece_ids[]=" . $pieceId);
            }
        }
        $query = join('&', $array); 
        return $query;
    }
}