<?php

namespace Yoeb\Otelz;

use Yoeb\Otelz\Const\OtelzCountry;
use Yoeb\Otelz\Const\OtelzLanguage;

class OtelzPlace {

    static function cities($country = OtelzCountry::XX, $lang = OtelzLanguage::TR) {
        $res = Otelz::post("/v1/data/city_data", [
            "lang"      => $lang,
            "country"   => $country,
        ]);
        $resJson = $res->json();
        if(!empty($resJson["errors"])){
            return Otelz::error( $resJson["errors"][0]["message"], $resJson["errors"][0]["code"]);
        }

        if($res->status() != 200){
            Otelz::errorThrow("Oh no!, status code: " . $res->status(), $res->status());
        }

       return Otelz::data("Cities listed.", $resJson["cities"]);
    }

    static function districts($city_reference, $lang = OtelzLanguage::TR) {
        $res = Otelz::post("/data/place_data", [
            "lang"              => $lang,
            "city_reference"    => $city_reference,
        ]);
        $resJson = $res->json();
        if(!empty($resJson["errors"])){
            return Otelz::error( $resJson["errors"][0]["message"], $resJson["errors"][0]["code"]);
        }

        if($res->status() != 200){
            Otelz::errorThrow("Oh no!, status code: " . $res->status(), $res->status());
        }

        return Otelz::data("Districts listed.", $resJson["districts"]);
    }

}
