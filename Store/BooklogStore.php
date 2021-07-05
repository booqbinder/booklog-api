<?php

namespace Booklog\Api\Store;

use Booklog\Api\BooklogApi;
use Booklog\Api\BooklogApiException;

/**
 * Booklog raktári rendelést kezelő osztály
 * Class BooklogStore
 * @package Booklog\Api\Store
 */
class BooklogStore extends BooklogApi
{
    /**
     * Kiszedés flag
     */
    const SALES_ORDER = 0;
    /**
     * Bevételezés flag
     */
    const PURCHASE_ORDER = 1;

    /**
     * Túrákra tétel, ha megvan adva a kiszedés után erre a túrára kerül rá.
     */
    const SHIPPING_METHOD_NONE = null;
    const SHIPPING_METHOD_CORRECTION = "Correction";
    const SHIPPING_METHOD_PERSONALPICKUP = "PersonalPickup";
    const SHIPPING_METHOD_PICKPACKPONT = "PickPackPont";
    const SHIPPING_METHOD_FURGEFUTAR = "Furgefutar";
    const SHIPPING_METHOD_BOOKLOG = "booklog";

    /**
     * Új Raktári rendelés kérés
     * @param $data
     * @return mixed
     * @throws BooklogStoreException
     */
    public function createStoreOrder($data)
    {
        return $this->sendRequest("storeorder.create", $data);
    }

    /**
     * Rendelés lezárása/nyugtázása
     * @param $data
     * @return mixed
     * @throws BooklogStoreException
     */
    public function closeStoreOrder($data)
    {
        return $this->sendRequest("storeorder.closeOrder", $data);
    }

    /**
     * Raktári rendelés törlése
     * @param $data
     * @return mixed
     * @throws BooklogStoreException
     */
    public function deleteStoreOrder($data)
    {
        return $this->sendRequest("storeorder.deleteOrder", $data);
    }

    /**
     * Termék(ek) frissítése egy rendelésen belül
     * @param $data
     * @return mixed
     * @throws BooklogStoreException
     */
    public function updateItems($data)
    {
        return $this->sendRequest("storeorder.updateItems", $data);
    }

    /**
     * Termék(ek) ellenörzése egy rendelésen belül
     * @param $data
     * @return mixed
     * @throws BooklogStoreException
     */
    public function checkItems($data)
    {
        return $this->sendRequest("storeorder.checkItems", $data);
    }

    /**
     * kiszedés eltérés elfogadása
     * @param $data
     * @return mixed
     * @throws BooklogStoreException
     */
    public function acceptMismatch($data)
    {
        return $this->sendRequest("storeorder.acceptMismatch", $data);
    }

    /**
     * kiszedés eltérés vissza küldése felülvizsgálásra
     * @param $data
     * @return mixed
     * @throws BooklogStoreException
     */
    public function modifyMismatch($data)
    {
        return $this->sendRequest("storeorder.modifyMismatch", $data);
    }

    /**
     * Dokumentum hozzáadása egy rendeléshez, mondjuk számla vagy szállító levél
     * @param $orderId
     * @param $file
     * @return mixed
     * @throws BooklogStoreException
     */
    public function addDocument($orderId, $file)
    {
        try {
            $reqparams = [
                [
                    "name" => "action",
                    "contents" => "storeorder.addDocument"
                ],[
                    "name" => "data[orderid]",
                    "contents" => $orderId
                ],[
                    "name"     => 'Document',
                    "contents" => fopen($file, 'r'),
                    "filename" => basename($file)
                ]
            ];
            return $this->makeApiCall($reqparams, "multipart");
        } catch (BooklogApiException $e) {
            throw new BooklogStoreException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Szállító cimke, pl.: Fürgefutár matrica hozzáadása
     * @param $orderId
     * @param $label
     * @return mixed
     * @throws BooklogStoreException
     */
    public function addLabel($orderId, $label)
    {
        try {
            $reqparams = [
                [
                    "name" => "action",
                    "contents" => "storeorder.addLabel"
                ],[
                    "name" => "data[orderid]",
                    "contents" => $orderId
                ],[
                    "name"     => "Document",
                    "contents" => fopen($label, "r"),
                    "filename" => basename($label)
                ]
            ];
            return $this->makeApiCall($reqparams, "multipart");
        } catch (BooklogApiException $e) {
            throw new BooklogStoreException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Termék létrehozása a rendszerben
     * @param $data
     * @return mixed
     * @throws BooklogStoreException
     */
    public function createProduct($data)
    {
        return $this->sendRequest("product.saveProduct", $data);
    }

    /**
     * Termék(ek) készletének lekérdezése
     * @param $data
     * @return mixed
     * @throws BooklogStoreException
     */
    public function checkStock($data)
    {
        return $this->sendRequest("product.checkStock", $data);
    }

    /**
     * Termék hozzáadása egy raktári rendeléshez
     * @param $data
     * @return mixed
     * @throws BooklogStoreException
     */
    public function addItems($data)
    {
        return $this->sendRequest("storeorder.addItems", $data);
    }

    /**
     * Allapot és információ lekérdezés
     * @param $data
     * @return mixed
     * @throws BooklogStoreException
     */
    public function getOrderInfo($data)
    {
        return $this->sendRequest("storeorder.getOrderInfo", $data);
    }

    /**
     * Státusz lekérdezése
     * @param $data
     * @return mixed
     * @throws BooklogStoreException
     */
    public function getOrderStatus($data)
    {
        return $this->sendRequest("storeorder.getOrderStatus", $data);
    }

    /**
     * Reklamáció létrehozása
     * @param $data
     * @return mixed
     * @throws BooklogStoreException
     */
    public function createClaim($data)
    {
        return $this->sendRequest("storeorder.createClaim", $data);
    }

    /**
     * Reklamáció lezárása
     * @param $data
     * @return mixed
     * @throws BooklogStoreException
     */
    public function closeClaim($data)
    {
        return $this->sendRequest("storeorder.closeClaim", $data);
    }

    protected function sendRequest($action, $data)
    {
        try {
            $reqparams = [
                "action" => $action,
                "data" => $data
            ];
            return $this->makeApiCall($reqparams);
        } catch (BooklogApiException $e) {
            throw new BooklogStoreException($e->getMessage(), $e->getCode(), $e);
        }
    }
}