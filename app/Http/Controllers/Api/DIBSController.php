<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Services\Apis\DIBS;
use App\Services\Orders\Manager;

class DIBSController extends ApiController
{

    public function createCheckout(Request $request) {
        return $this->json(
            DIBS::createCheckout($request->post('order'))
        );
    }

    public function initializedCheckout(Request $request) {
        return $this->json(
            DIBS::initializedCheckout($request->post('payId'), $request->post('order'))
        );
    }

    public function subscription() {
        return $this->json(
            DIBS::subscription()
        );
    }

    public function chargeSubscription() {
        return $this->json(
            DIBS::chargeSubscription()
        );
    }

    public function fetchCheckout(Request $request) {
        return $this->json(
            DIBS::fetchCheckout($request->post('order_id'))
        );
    }

    public function updateCheckout(Request $request){
        return $this->json(
            DIBS::updateCheckout($request->post('order_id'), $request->post('order'))
        );
    }

    public function push(Request $request) {
        $data = DIBS::push($request->get('checkout_uri'));
        $order = Manager::createDIBSOrder($data);
        return $this->json(DIBS::updateMerchatReference($order->properties->DIBS_order_id, $order->id));
    }

}
