<?php

namespace Yoeb\Otelz;

use Illuminate\Support\Facades\Http;

class OtelzInstallment {

    static function list(array $facilityReferences = []) {
        $res = Otelz::post("data/installment_data", ["facility_references" => $facilityReferences]);
        if(!empty($res["errors"])){
            Otelz::error($res["errors"][0]["message"], $res["errors"][0]["code"]);
        }

        if($res->status() != 200){
            Otelz::error("Oh no!, status code: " . $res->status(), $res->status());
        }

       return $res->json()["installments"]["12004"];
    }

}
