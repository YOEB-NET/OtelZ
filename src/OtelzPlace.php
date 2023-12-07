<?php

namespace Yoeb\Otelz;

use Yoeb\Otelz\Const\OtelzCountry;
use Yoeb\Otelz\Const\OtelzLanguage;
use Illuminate\Support\Facades\Http;

class OtelzPlace {

    static function cities($country = OtelzCountry::XX, $lang = OtelzLanguage::TR) {
        $res = Otelz::post("/v1/data/city_data", [
            "lang"      => $lang,
            "country"   => $country,
        ]);

        if(!empty($res["errors"])){
            Otelz::error($res["errors"][0]["message"], $res["errors"][0]["code"]);
        }

        if($res->status() != 200){
            Otelz::error("Oh no!, status code: " . $res->status(), $res->status());
        }

       return $res->json()["cities"];
    }

    static function districts($city_reference, $lang = OtelzLanguage::TR) {
        $res = Otelz::post("/data/place_data", [
            "lang"              => $lang,
            "city_reference"    => $city_reference,
        ]);

        if(!empty($res["errors"])){
            Otelz::error($res["errors"][0]["message"], $res["errors"][0]["code"]);
        }

        if($res->status() != 200){
            Otelz::error("Oh no!, status code: " . $res->status(), $res->status());
        }

       return $res->json()["districts"];
    }

}
