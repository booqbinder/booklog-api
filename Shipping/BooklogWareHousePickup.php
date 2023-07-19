<?php

namespace Booklog\Api\Shipping;

use Booklog\Api\BooklogApi;
use Booklog\Api\BooklogApiException;

/**
 * Booklog személyes átvétel osztály
 * Class BooklogWarehousePickup
 * @package Booklog\Api\Shipping
 */
class BooklogWarehousePickup extends BooklogShipping
{
    protected static $type = "booklog_warehouse_pickup";
}