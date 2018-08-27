<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Services\Apis\BringPostalCode;
use App\Services\Apis\Netaxept;
use App\Services\Apis\Cargonizer;

class ThirdPartyApisController extends ApiController
{
    /** 
     * Bring Postal Code
    */
    public function lookupPostalCode($pnr = null){
        return $this->json(
            BringPostalCode::lookupPostalCode($pnr)
        );
    }

    /**
     * Netaxept
     */

    public function register(Request $request){
        return $this->json(
            Netaxept::register($request->input('orderNumber'), $request->input('amount'))
        );
    }

    public function terminal($transactionId, $isMobile = null){
        return $this->json(
            Netaxept::terminal($transactionId, $isMobile)
        );
    }

    public function auth(){
        return $this->json(
            Netaxept::auth($request->input('transactionId'))
        );
    }

    public function capture(){
        return $this->json(
            Netaxept::capture($request->input('transactionId'), $request->input('transactionAmount'))
        );
    }

    public function query($transactionId = null){
        return $this->json(
            Netaxept::query($transactionId)
        );
    }


    /**
     * Cargonizer
     */

    public function consignement(Request $request){
        return $this->json(
            Cargonizer::createConsignment()
        );
    }

    public function transportAgreement(){
        return $this->json(
            Cargonizer::getTransportAgreement()
        );
    }

    public function pickupPoints($postcode){
        return $this->json(
            Cargonizer::getPickupPoints($postcode)
        );
    }

    public function estimateShipping(Request $request){
        return $this->json(
            Cargonizer::estimateShippingCost($request->all())
        );
    }

    public function printers(){
        return $this->json(
            Cargonizer::getPrinters()
        );
    }

    public function labelsPDF($consignmentIds){
        return $this->json(
            Cargonizer::getLabelsAsPDF($consignmentIds)
        );
    }
    public function billwayPDF($consignmentIds){
        return $this->json(
            Cargonizer::getWaybillAsPDF($consignmentIds)
        );
    }
    public function goodsDeclarationPDF($consignmentIds){
        return $this->json(
            Cargonizer::getGoodsDeclarationAsPDF($consignmentIds)
        );
    }
    
}
