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

    /**
     * Szállítás létrehozása
     * @param $data
     * @return mixed
     * @throws BooklogShippingException
     */
    public function createOrder($data)
    {
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