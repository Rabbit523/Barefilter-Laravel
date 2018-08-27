<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\Orders\Manager as OrdersManager;
use App\Services\Orders\Orders;
use App\Services\Orders\Exporter;
use App\Services\Orders\Subscriptions;

class OrdersController extends ApiController
{

    public function dashboard($start, $end) {
        return $this->json(
            Orders::getDashboard($start, $end)
        );
    }

    public function productlist($start, $end) {
        return $this->json(
            Orders::getProductsList($start, $end)
        );
    }

    public function profile($id) {
        return $this->json(
            Orders::profile($id)
        );
    }

    public function history($uid) {
        return $this->json(
            Orders::getHistory($uid)
        );
    }

    public function search($id) {
        return $this->json(
            Orders::search($id)
        );
    }

    public function timeframedHistory($uid, $sid, $start, $end){
        return $this->json(
            Orders::getTimeframedHistory($uid, $sid, $start, $end)
        );
    }

    public function browseSubscriptions($start, $end){
        return $this->json(
            Orders::browseSubscriptions($start, $end)
        );
    }

    public function place(Request $request) {
        return $this->json(
            OrdersManager::placeOrder($request->all())
        );
    }

    public function deleteSubscriptionOrder(Request $request) { 
        return $this->json(
            OrdersManager::SubScriptionDeleteOrder($request->all())
        );
    }

    public function delete(Request $request) {
        return $this->json(
            OrdersManager::softDeleteOrder($request->all())
        );
    }

    public function oneTimeTransactions($uid) {
        return $this->json(
            Orders::getMyOneTimeTransactions($uid)
        );
    }

    public function subscriptions($uid) {
        return $this->json(
            Orders::getMySubscriptions($uid)
        );
    }

    public function subscriptionTypes() {
        return $this->json(
            Orders::getSubscriptionTypes()
        );
    }

    public function transferSubscription(Request $request) {
        return $this->json(
            OrdersManager::transferSubscription($request->all())
        );
    }

    public function cancelSubscription(Request $request) {
        return $this->json(
            OrdersManager::cancelSubscription($request->all())
        );
    }


    public function exportToExcel($uid, $sid, $start, $end){
        $pathToFile = Exporter::exportToExcel($uid, $sid, $start, $end);
        return response()->download($pathToFile)->deleteFileAfterSend(true);;
    }


    /**/
    public function today(){
        return $this->json(
            Subscriptions::today()
        );
    }

    public function monthly(){
        return $this->json(
            Subscriptions::monthly()
        );
    }

}
