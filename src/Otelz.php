<?php

namespace Yoeb\Otelz;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Http;

class Otelz {

    static function url($url) {
        if(env("OTELZ_TEST_MODE", false)){
            return "https://fullconnect-api.dev.otelz.com/" . $url;
        }

        return "https://fullconnect-api.otelz.com/" . $url;
    }

    static function body($bodys = []) {
        $base = [
            "api_version"            => "1.0.0",
            "partner_id"             => env("OTELZ_PARTNER_ID"),
        ];

        $base = array_merge($bodys, $base);
        return $base;
    }

    static function post($url, $bodys = [], $headers = []) {

        $res = Http::withBasicAuth(env("OTELZ_USERNAME", 'GlobalTest1'), env("OTELZ_PASSWORD", 'GlobalTest1'))
        ->timeout(env("OTELZ_TIMEOUT", 120))
        ->withHeaders($headers)->post(Otelz::url($url), Otelz::body($bodys));
        return $res;
    }


    public static function data($message, $data = null)
    {
        if($data === null){
            $data = new \stdClass;
        }

        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data
        ], 200, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    public static function error($message = "", $errorCode = "V000")
    {
        return response()->json([
            'status'        => false,
            'message'       => $message,
            'error_code'    => $errorCode,
        ]);
    }

    public static function errorThrow($message = "", $errorCode = "V000")
    {
        throw new HttpResponseException(response()->json([
            'status'        => false,
            'message'       => $message,
            'error_code'    => $errorCode,
        ]));
    }

}

