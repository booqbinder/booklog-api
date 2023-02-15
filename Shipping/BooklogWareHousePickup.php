<?php

namespace Booklog\Api\Shipping;

use Booklog\Api\BooklogApi;
use Booklog\Api\BooklogApiException;

/**
 * Booklog személyes átvétel osztály
 * Class BooklogWarehousePickup
 * @package Booklog\Api\Shipping
 */
class BooklogWarehousePickup extends BooklogApi
{

    /**
     * Szállítás létrehozása
     * @param $data
     * @return mixed
     * @throws BooklogShippingException
     */
    public function createOrder($data)
    {
        $data["serviceprovider_type"] = "booklog_warehouse_pickup";
        try {
            $reqparams = [
                "action" => "shippingorder.create",
                "data" => $data
            ];
            return $this->makeApiCall($reqparams);
        } catch (BooklogApiException $e) {
            throw new BooklogShippingException($e->getMessage(), $e->getCode(), $e);
        }
    }
}