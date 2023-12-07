<?php

namespace Yoeb\Otelz;

use Yoeb\Otelz\Const\OtelzCountry;
use Yoeb\Otelz\Const\OtelzCurrency;
use Yoeb\Otelz\Const\OtelzDevice;
use Yoeb\Otelz\Const\OtelzLanguage;
use Yoeb\Otelz\Const\OtelzPayment;
use Illuminate\Support\Facades\Http;

class OtelzReservation {
    static protected $lang = "tr";
    static protected $reservation_guid;
    static protected $customer;
    static protected $room_info;
    static protected $room_reference;
    static protected $guests = [];
    static protected $note;
    static protected $bed_key;
    static protected $agree_save_info = true;
    static protected $agree_read_consent = true;
    static protected $agree_read_rules = true;
    static protected $res_for_who;
    static protected $estimated_checkin_time;
    static protected $is_honey_moon = false;
    static protected $honey_moon_note;
    static protected $wedding_date;
    static protected $customer_note;
    static protected $payment_option;
    static protected $payment_type = OtelzPayment::UNKNOWN;
    static protected $currency = OtelzCurrency::TRY;
    static protected $price_formatter = ['decimal_digit_number' => 2];
    static protected $device_type = OtelzDevice::UNKNOWN;
    static protected $facility_references = [];
    static protected $facility_reference;
    static protected $price_keys = [];
    static protected $user_country = OtelzCountry::XX;
    static protected $country_code = OtelzCountry::XX;
    static protected $ip;
    static protected $latitude;
    static protected $longitude;
    static protected $start_date;
    static protected $end_date;
    static protected $party = [
        "adults"     => 0,
        "children"   => 0,
    ];
    static protected $request_type;


    public static function startDate($start_date) {
        self::$start_date = $start_date;
        return new static;
    }

    public static function endDate($end_date) {
        self::$end_date = $end_date;
        return new static;
    }

    public static function addChildren($children = null) {
        self::$party["children"] = self::$party["children"] + ($children ?? 1);
        return new static;
    }

    public static function addAdult($adult = null) {
        self::$party["adults"] = self::$party["adults"] + ($adult ?? 1);
        return new static;
    }

    public static function party($party) {
        self::$party = $party;
        return new static;
    }

    public static function requestType($request_type) {
        self::$request_type = $request_type;
        return new static;
    }

    public static function facilityReference($facilityReference){
        self::$facility_reference = $facilityReference;
        return (new static);
    }

    public static function availability(){
        $res = Otelz::post("/v1/detail/availability", [
            "api_version"           => "1.0.0",
            "facility_reference"    => self::$facility_reference,
            "start_date"            => self::$start_date,
            "end_date"              => self::$end_date,
            "party"                 => self::$party,
            "request_type"          => self::$request_type,
            "lang"                  => self::$lang,
            "currency"              => self::$currency,
            "user_country"          => self::$user_country,
            "device_type"           => self::$device_type
        ]);


        if(!empty($res["errors"])){
            Otelz::error($res["errors"][0]["message"], $res["errors"][0]["code"]);
        }

        if($res->status() != 200){
            Otelz::error("Oh no!, status code: " . $res->status(), $res->status());
        }

        return $res->json();
    }

    public static function facilityReferencesAdd($facilityReference){
        self::$facility_references[] = $facilityReference;
        return (new static);
    }
    public static function facilityReferences($facilityReferences){
        self::$facility_references = $facilityReferences;
        return (new static);
    }

    public static function priceKeyAdd($key, $unit_of_room){
        self::$price_keys[] = [
            "key"           => $key,
            "unit_of_room"  => $unit_of_room,
        ];
        return (new static);
    }

    public static function priceKeys($priceKeys){
        self::$price_keys = $priceKeys;
        return (new static);
    }

    public static function userCountry($userCountry){
        self::$user_country = $userCountry;
        return (new static);
    }

    public static function countryCode($countryCode){
        self::$country_code = $countryCode;
        return (new static);
    }

    public static function ip($ip){
        self::$ip = $ip;
        return (new static);
    }

    public static function latitude($latitude){
        self::$latitude = $latitude;
        return (new static);
    }

    public static function longitude($longitude){
        self::$longitude = $longitude;
        return (new static);
    }


    public static function start(){
        $res = Otelz::post("/reservation/start", [
            "facility_reference"=> self::$facility_reference,
            "price_keys"        => self::$price_keys,
            "currency"          => self::$currency,
            "lang"              => self::$lang,
            "price_formatter"   => self::$price_formatter,
            "user_country"      => self::$user_country,
            "device_type"       => self::$device_type,
            "customer_geo"      => [
                "country_code"      => self::$country_code,
                "ip"                => self::$ip,
                "latitude"          => self::$latitude,
                "longitude"         => self::$longitude
            ]
        ]);


        if(!empty($res["errors"])){
            Otelz::error($res["errors"][0]["message"], $res["errors"][0]["code"]);
        }

        if($res->status() != 200){
            Otelz::error("Oh no!, status code: " . $res->status(), $res->status());
        }

        return $res->json();
    }

    public static function lang($lang){
        self::$lang = $lang;
        return (new static);
    }

    public static function reservationGuid($reservationGuid){
        self::$reservation_guid = $reservationGuid;
        return (new static);
    }

    public static function customer($name, $surname, $phone_code, $phone_number, $email, $country, $gender){
        self::$customer = [
            "name"          => $name,
            "surname"       => $surname,
            "phone_code"    => $phone_code,
            "phone_number"  => $phone_number,
            "email"         => $email,
            "country"       => $country,
            "gender"        => $gender,
        ];
        return (new static);
    }

    public static function roomInfo($roomInfo){
        self::$room_info = $roomInfo;
        return (new static);
    }

    public static function roomReference($room_reference){
        self::$room_info["room_reference"] = $room_reference;
        return (new static);
    }

    public static function guests($guests){
        self::$room_info["guests"] = $guests;
        return (new static);
    }

    public static function guestAdd($name, $surname, $age){
        self::$room_info["guests"][] = (object) [
            "neme"      => $name,
            "surname"   => $surname,
            "age"       => $age
        ];
        return (new static);
    }

    public static function note($note){
        self::$room_info["note"] = $note;
        return (new static);
    }

    public static function bedKey($bed_key){
        self::$room_info["bed_key"] = $bed_key;
        return (new static);
    }

    public static function agreeSaveInfo($agreeSaveInfo){
        self::$agree_save_info = $agreeSaveInfo;
        return (new static);
    }

    public static function agreeReadConsent($agreeReadConsent){
        self::$agree_read_consent = $agreeReadConsent;
        return (new static);
    }

    public static function agreeReadRules($agreeReadRules){
        self::$agree_read_rules = $agreeReadRules;
        return (new static);
    }

    public static function resForWho($resForWho){
        self::$res_for_who = $resForWho;
        return (new static);
    }

    public static function estimatedCheckinTime($estimatedCheckinTime){
        self::$estimated_checkin_time = $estimatedCheckinTime;
        return (new static);
    }

    public static function isHoneyMoon($isHoneyMoon){
        self::$is_honey_moon = $isHoneyMoon;
        return (new static);
    }

    public static function honeyMoonNote($honeyMoonNote){
        self::$honey_moon_note = $honeyMoonNote;
        return (new static);
    }

    public static function weddingDate($weddingDate){
        self::$wedding_date = $weddingDate;
        return (new static);
    }

    public static function customerNote($customerNote){
        self::$customer_note = $customerNote;
        return (new static);
    }

    public static function paymentOption($paymentOption){
        self::$payment_option = $paymentOption;
        return (new static);
    }

    public static function paymentType($paymentType){
        self::$payment_option["payment_type"] = $paymentType;
        return (new static);
    }

    public static function currency($currency){
        self::$currency = $currency;
        return (new static);
    }

    public static function deviceType($deviceType){
        self::$device_type = $deviceType;
        return (new static);
    }

    public static function saveCustomer(){
        $res = Otelz::post("/reservation/save/customer", [
            "lang"                   => self::$lang,
            "api_version"            => "1.0.0",
            "reservation_guid"       => self::$reservation_guid,
            "customer"               => self::$customer,
            "agree_save_info"        => self::$agree_save_info,
            "agree_read_consent"     => self::$agree_read_consent,
            "agree_read_rules"       => self::$agree_read_rules,
            "res_for_who"            => self::$res_for_who,
            "estimated_checkin_time" => self::$estimated_checkin_time,
            "currency"               => self::$currency,
            "price_formatter"        => self::$price_formatter,
            "user_country"           => self::$user_country,
            "device_type"            => self::$device_type,
        ]);


        if(!empty($res["errors"])){
            Otelz::error($res["errors"][0]["message"], $res["errors"][0]["code"]);
        }

        if($res->status() != 200){
            Otelz::error("Oh no!, status code: " . $res->status(), $res->status());
        }

        return $res->json();
    }

    public static function saveRoom(){
        $res = Otelz::post("/v1/reservation/save/customer", [
            "lang"                   => self::$lang,
            "api_version"            => "1.0.0",
            "reservation_guid"       => self::$reservation_guid,
            "room_info"              => self::$room_info,
            "is_honey_moon"          => self::$is_honey_moon,
            "honey_moon_note"        => self::$honey_moon_note,
            "wedding_date"           => self::$wedding_date,
            "customer_note"          => self::$customer_note,
            "currency"               => self::$currency,
            "price_formatter"        => self::$price_formatter,
            "user_country"           => self::$user_country,
            "device_type"            => self::$device_type
        ]);


        if(!empty($res["errors"])){
            Otelz::error($res["errors"][0]["message"], $res["errors"][0]["code"]);
        }

        if($res->status() != 200){
            Otelz::error("Oh no!, status code: " . $res->status(), $res->status());
        }

        return $res->json();
    }

    public static function saveCustomerAndRoom(){
        $res = Otelz::post("/reservation/save/customerandroom", [
            "api_version" => "1.0.0",
            "reservation_guid" => self::$reservation_guid,
            "customer" => [
                "name" => "test",
                "surname" => "user",
                "phone_code" => "90",
                "phone_number" => "5453040404",
                "email" => "test@test.com",
                "gender" => 2,
                "country" => "TR"
            ],
            "agree_save_info" => "true",
            "agree_read_consent" => "true",
            "agree_read_rules" => self::$agree_read_rules,
            "res_for_who" => self::$res_for_who,
            "estimated_checkin_time" => self::$estimated_checkin_time,
            "room_info" => [
                [
                    "room_reference" => self::$room_reference,
                    "guests" => [
                        [
                            "name" => "test",
                            "surname" => "user",
                            "age" => 35
                        ]
                    ],
                    "note" => "test Note",
                    "bed_key" => ""
                ]
            ],
            "is_honey_moon" => false,
            "honey_moon_note" => "",
            "wedding_date" => "",
            "customer_note" => "",
            "currency" => self::$currency,
            "lang" => "tr",
            "price_formatter" => [
                "decimal_digit_number" => 2
            ],
            "user_country" => "TR",
            "device_type" => self::$device_type,
            "web_hook_url" => ""
        ]);


        if(!empty($res["errors"])){
            Otelz::error($res["errors"][0]["message"], $res["errors"][0]["code"]);
        }

        if($res->status() != 200){
            Otelz::error("Oh no!, status code: " . $res->status(), $res->status());
        }

        return $res->json();
    }

    public static function saveAndFinalize(){
        $res = Otelz::post("/v1/reservation/finalize/saveandfinalize", [
            "lang"                      => self::$lang,
            "reservation_guid"          => self::$reservation_guid,
            "customer"                  => self::$customer,
            "room_info"                 => self::$room_info,
            "agree_save_info"           => self::$agree_save_info,
            "agree_read_consent"        => self::$agree_read_consent,
            "agree_read_rules"          => self::$agree_read_rules,
            "res_for_who"               => self::$res_for_who,
            "estimated_checkin_time"    => self::$estimated_checkin_time,
            "is_honey_moon"             => self::$is_honey_moon,
            "honey_moon_note"           => self::$honey_moon_note,
            "wedding_date"              => self::$wedding_date,
            "customer_note"             => self::$customer_note,
            "payment_option"            => self::$payment_option,
            "currency"                  => self::$currency,
            "price_formatter"           => self::$price_formatter,
            "device_type"               => self::$device_type,
        ]);
        $res = Otelz::post("/v1/reservation/finalize/saveandfinalize", [
                "reservation_guid" => self::$reservation_guid,
                "customer" => [
                    "name" => "Test",
                    "surname" => "User",
                    "phone_code" => "90",
                    "phone_number" => "1231231212",
                    "email" => "example@otelz.com",
                    "country" => "TR"
                ],
                "room_info" => [
                    [
                        "room_reference" => "12004",
                        "guests" => [
                            [
                                "name" => "Test",
                                "surname" => "User",
                                "age" => 35
                            ]
                        ],
                        "note" => "test Note 2",
                        "bed_key" => ""
                    ]
                ],
                "payment_option" => [
                    "payment_type" => "online",
                    "payment_redirect_uri" => "https://fullconnect-api.dev.otelz.com" . "/v1/partner-return-test",
                    "card_info" => [
                        "card_number" => "4256690000000001",
                        "holder_name" => "Mesut Taşcı",
                        "year" => "2025",
                        "month" => "01",
                        "cvv" => "001"
                    ]
                ],
                "agree_save_info" => true,
                "agree_read_consent" => true,
                "agree_read_rules" => true,
                "res_for_who" => "ForMyself",
                "estimated_checkin_time" => "14:00",
                "is_honey_moon" => false,
                "honey_moon_note" => "",
                "wedding_date" => "",
                "customer_note" => "",
                "currency" => "tr",
                "lang" => "tr",
                "price_formatter" => [
                    "decimal_digit_number" => 2
                ],
                "user_country" => "TR",
                "device_type" => 2,
                "web_hook_url" => ""
        ]);

        if(!empty($res["errors"])){
            Otelz::error($res["errors"][0]["message"], $res["errors"][0]["code"]);
        }

        if($res->status() != 200){
            Otelz::error("Oh no!, status code: " . $res->status(), $res->status());
        }

        return $res->json();
    }
}
