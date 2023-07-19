<?php

namespace Booklog\Api\Shipping;

use Booklog\Api\BooklogApi;
use Booklog\Api\BooklogApiException;

/**
 * Booklog szállítás létrehozó osztály
 * Class BooklogShipping
 * @package Booklog\Api\Shipping
 */
class BooklogShipping extends BooklogApi
{

    protected static $type = "booklog_shipping";

    /**
     * Szállítás létrehozása
     * @param $data
     * @return mixed
     * @throws BooklogShippingException
     */
    public function createOrder($data)
    {
        $data["serviceprovider_type"] = static::$type;
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

    public function getClerkInfoByTrackingId($tracking_id)
    {
        try {
            $reqparams = [
                "action" => "shippingorder.getClerkInfo",
                "data" => [
                    "tracking_id" => $tracking_id
                ]
            ];
            return $this->makeApiCall($reqparams);
        } catch (BooklogApiException $e) {
            throw new BooklogShippingException($e->getMessage(), $e->getCode(), $e);
        }
    }
}