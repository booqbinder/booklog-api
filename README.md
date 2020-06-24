# booklog-api

Booklog api library

## Raktári rendelés

Egy raktári rendelés feladásához a következő implementációt kell használni

```php
$apiKey = "test-key";
$booklogStoreApiClient = new BooklogStore($apiKey);
```

A létrejött $booklogStoreApiClient változó tartalmazza az api kommunikációhoz szükséges beállításokat.

Egy rendelés feladásához a következő képpen kell eljárnunk:
```php
$orderId = 321; // az api-t meghívó rendszerben lévő rendelés száma 

$booklogStoreApiClient->createStoreOrder([
     "ordertype" => BooklogStore::SALES_ORDER, // Bevételezéshez BooklogStore::PURCHASE_ORDER
     "orderid" => $orderId, // az api-t meghívó rendszerben lévő rendelés száma
     "items" => [
         [
             "barcode" => "123456789",
             "qty" => 1,
             "name" => "Dummy product",
             "listprice" => 500
         ]
     ], // a rendelésben szereplő tételek
     "shippingmethod" => BooklogStore::SHIPPING_METHOD_FURGEFUTAR, // SHIPPING_METHOD_NONE ...
     "locationid" => 1,
     "location" => "Partner címe",
     "locationzip" => 1191,
     "locationcity" => "Budapest",
     "locationaddress" => "Lehel utca 15",
]);
```

Amennyiben a rendeléshez dokumentumot szeretnénk hozzáadni (pl.: egy számlát vagy szállító levelet) akkor a következő kódot kell használnunk:
```php
$booklogStoreApiClient->addDocument($orderId, "/tmp/document.pdf");
```
Ha mondjuk futár cimkét szeretnénk akkor pedig így:
```php
$booklogStoreApiClient->addLabel($orderId, "/tmp/label.pdf");
```

A Booklog rendszerben lévő termékeket a következő képpen tudjuk rögzíteni:

```php
$booklogStoreApiClient->createProduct([
  "barcode" => "123456789", // a termék vonalkodója az azonosításhoz
  "name" => "Dummy product", // a termék megnevezése
  "listprice" => 500 // a termék lista ára
]);
```
Amennyiben egy rendelést szeretnénk törölni:
```php
$booklogStoreApiClient->deleteStoreOrder(["orderid" => $orderId]);
```
Vigyázat! A lehívott raktári rendelést már nem lehet törölni vagy módosítani!

Egy le nem hívott rendelésben lehetőségünk van egy termék vagy termékek darabászámának módosítására:

```php
$booklogStoreApiClient->updateItems([
    "orderid" => $orderId,
    "items" => [
        [
            "barcode" => "123456789",
            "name" => "Dummy product",
            "listprice" => 500,
            "qty" => 10
        ]
    ]
]);
```

Egy raktári rendelés véglegesítése, amennyiben elfogadjuk a raktár által teljesített tételeket

```php
$booklogStoreApiClient->closeStoreOrder(["orderid" => $orderId]);
```

Ha szeretnénk egyes termékeknek megtudni a raktár készletét, akkor azt így lehet:

```php
$booklogStoreApiClient->checkStock([
    "orderid" => $orderId,
    "items" => [
        [
            "barcode" => "123456789"
        ]
    ]
]);
```

## Szállítás rendelés

Szállítás felvétele api-n kereszül

```php
$apiKey = "test-key";
$booklogShippingApiClient = new BooklogShipping($apiKey);
```

Szállítás létrehozása api-n keresztül

```php
$booklogShippingApiClient->createOrder([
    "orderid" => $orderId,  // Ha a szállításhoz tartozik booklog-os kiszedés akkor azt itt meglehet adni,
                            //más esetben null, vagy nem kötelező megadni.
    "origin" => "Raktár", // Felvétel hely neve
    "origin_address" => [
        [
            "country" => 348, // Magyarország
            "postcode" => "2220",
            "city" => "Vecsés",
            "address" => "Raktár címe"
        ]
    ],
    "destination" => "Cimzett", // Kiszállítás helyének neve
    "destination_address" => [
        [
            "country" => 348, // Magyarország
            "postcode" => "1191",
            "city" => "Budapest",
            "address" => "Lehel utca 15"
        ]
    ],
]);
```