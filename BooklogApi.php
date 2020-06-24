<?php

namespace Booklog\Api;

use Booklog\Api\Exceptions\ServerErrorException;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;

class BooklogApi
{
    protected $client = null;
    protected $apiUrl = null;
    protected $apiKey = null;

    const DEFAULT_URL = "https://www.booklog.hu/api/";

    public function __construct($apiKey, $apiUrl = null)
    {
        $this->apiKey = $apiKey;
        $this->apiUrl = $apiUrl ?? self::DEFAULT_URL;
        $parsedUrl = parse_url($this->apiUrl);
        $cookieJar = CookieJar::fromArray([
            "XDEBUG_SESSION" => "PHPSTORM"
        ], $parsedUrl["host"]);
        $this->client = new \GuzzleHttp\Client([
            "cookies" => $cookieJar,
            "timeout" => 60 * 2,
            "connect_timeout" => 60 * 2,
            "headers" => [
                "apikey" => $this->apiKey,
                "content-type" => "application/json"
            ]
        ]);
    }

    protected function makeApiCall($reqparams, $type = "json")
    {
        try {
            $request = $this->client->request(
                "POST",
                $this->apiUrl,
                [
                    $type => $reqparams
                ]
            );
            $response_contents = $request->getBody()->getContents();
            $response = json_decode($response_contents, true);
            if (empty($response) || $response['success'] !== true) {
                if (!empty($response['msg'])) {
                    throw new BooklogApiException($response['msg'], 600);
                }
                throw new BooklogApiException("Error in processing request!\n" . $response_contents, 600);
            }
            return $response;
        } catch (BadResponseException $e) {
            $response_contents = $e->getResponse()->getBody()->getContents();
            debug($response_contents);
            $response = json_decode($response_contents, true);
            if (empty($response) || $response['success'] !== true) {
                if (!empty($response['msg'])) {
                    throw new BooklogApiException($response['msg'], $e->getResponse()->getStatusCode(), $e);
                }
            }
            throw new ServerErrorException(
                "Bad response from the Booklog server!\n" . $e->getResponse()->getBody()->getContents(),
                $e->getResponse()->getStatusCode(),
                $e
            );
        } catch (ConnectException $e) {
            throw new ServerErrorException(
                "Cannot connect to the Booklog server!",
                408,
                $e
            );
        } catch(GuzzleException $e) {
            throw new ServerErrorException(
                "Bad request from the Booklog server!",
                1,
                $e
            );
        }
    }

}