<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use App\Services\Stores\Frontend as Stores;
use App\Services\Core\Frontend as Pages;
use App\Services\Orders\Orders;

class PagesController extends Controller
{

    public function index() {
        return view('pages.home', Stores::getHomePage());
    }

    public function dashboard() {
        if(!Auth::check()){
            return redirect()->route('home');
        }
        return view('pages.dashboard', [
            "page" => "dashboard"
        ]);
    }

    public function subscription() {
        return view('pages.subscription', Pages::getSubscriptionPage());
    }

    public function partner() {
        return view('pages.partner', Pages::getPartnerPage());
    }

    public function search() {
        return view('pages.search', Stores::getSearchPage());
    }

    public function about() {
        return view('pages.about-us', Pages::getAboutPage());
    }

    public function support() {
        return view('pages.support', Pages::getSupportPage());
    }

    public function contact() {
        return view('pages.contact', Pages::getContactPage());
    }

    public function customerService() {
        return view('pages.customer-service',Pages::getCustomerServicePage());
    }

    public function sitemap() {
        return view('pages.sitemap', [
            "page" => "sitemap"
        ]);
    }

    public function tos() {
        return view('pages.tos', Pages::getTosPage());
    }

    public function orderCompleted($orderId = null) {
        return view("pages.order-completed", ["page" => "order-completed", "order" => Orders::search($orderId)]);
    }
}
