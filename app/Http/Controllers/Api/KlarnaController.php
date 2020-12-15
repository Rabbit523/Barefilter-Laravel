<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Services\Apis\Klarna;
use App\Services\Orders\Manager;

class KlarnaController extends ApiController
{

    public function createCheckout(Request $request) {
        return $this->json(
            Klarna::createCheckout($request->post('order'))
        );
    }

    public function fetchCheckout(Request $request) {
        return $this->json(
            Klarna::fetchCheckout($request->post('order_id'))
        );
    }

    public function updateCheckout(Request $request){
        return $this->json(
            Klarna::updateCheckout($request->post('order_id'), $request->post('order'))
        );
    }

    public function push(Request $request) {
        $data = Klarna::push($request->get('checkout_uri'));
        $order = Manager::createKlarnaOrder($data);
        return $this->json(Klarna::updateMerchatReference($order->properties->klarna_order_id, $order->id));
    }

}
