<?php

namespace App\Services\Apis;
use GuzzleHttp\Psr7\Request;

class ApiClient{
    
    protected static function get($url, $parameters){
        $client = new \GuzzleHttp\Client(['http_errors' => false]);
        $res = $client->request('GET', $url, $parameters);
        return $res->getBody()->getContents();
    }

    protected static function post($url, $parameters){
        $client = new \GuzzleHttp\Client(['http_errors' => false]);
        $res = $client->request('POST', $url, $parameters);
        return $res->getBody()->getContents();
    }


    private static function normalizeSimpleXML($obj, &$result) {
        $data = $obj;
        if (is_object($data)) {
            $data = get_object_vars($data);
        }
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $res = null;
                self::normalizeSimpleXML($value, $res);
                if (($key == '@attributes') && ($key)) {
                    $result = $res;
                } else {
                    $result[$key] = $res;
                }
            }
        } else {
            $result = $data;
        }
        return $result;
    }

    protected static function parseXMLString($xmlString){
        self::normalizeSimpleXML(simplexml_load_string($xmlString), $result);
        return $result;
    }
}