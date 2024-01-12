<?php

namespace Yoeb\Otelz;

use Illuminate\Support\Facades\Http;

class OtelzInstallment {

    static function list(array $facilityReferences = []) {
        $res = Otelz::post("data/installment_data", ["facility_references" => $facilityReferences]);
        $resJson = $res->json();
        if(!empty($resJson["errors"])){
            return Otelz::error( $resJson["errors"][0]["message"], $resJson["errors"][0]["code"]);
        }

        if($res->status() != 200){
            Otelz::errorThrow("Oh no!, status code: " . $res->status(), $res->status());
        }

       return $resJson["installments"]["12004"];
    }

}
