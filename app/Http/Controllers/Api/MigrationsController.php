<?php

namespace App\Http\Controllers\Api;

use App\Services\Core\Migrations;
use App\Services\Orders\Manager as OrdersManager;

class MigrationsController extends ApiController
{

    public function restore() {
        set_time_limit(0);
        return $this->json(
            Migrations::restore()
        );
    }

    public function categories() {
        return $this->json(
            Migrations::categories()
        );
    }

    public function products() {
        return $this->json(
            Migrations::products()
        );
    }
    public function patchProducts($handle) {
        return $this->json(
            Migrations::patchProducts($handle)
        );
    }

    public function productSizes() {
        return $this->json(
            Migrations::productSizes()
        );
    }

    public function productImages() {
        return $this->json(
            Migrations::productImages()
        );
    }

    public function members() {
        return $this->json(
            Migrations::members()
        );
    }

    public function orders() {
        return $this->json(
            Migrations::orders()
        );
    }
    public function orderProducts() {
        return $this->json(
            Migrations::orderProducts()
        );
    }

    public function discoverSubscriptions() {
        return $this->json(
            OrdersManager::discoverSubscriptions()
        );
    }
}
