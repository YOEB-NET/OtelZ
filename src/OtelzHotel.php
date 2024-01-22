<?php

namespace Yoeb\Otelz;

use Yoeb\Otelz\Const\OtelzCountry;
use Yoeb\Otelz\Const\OtelzCurrency;
use Yoeb\Otelz\Const\OtelzDevice;
use Yoeb\Otelz\Const\OtelzLanguage;

class OtelzHotel {
    static protected $lang                  = OtelzLanguage::TR;
    static protected $country               = OtelzCountry::XX;
    static protected $city_reference        = null;
    static protected $district_reference    = null;
    static protected $facility_reference    = null;

    static protected $latitude;
    static protected $longitude;
    static protected $distance;
    static protected $facility_references   = [];
    static protected $type;
    static protected $filter;
    static protected $startDate;
    static protected $endDate;
    static protected $party;
    static protected $adults;
    static protected $children              = [];
    static protected $currency              = OtelzCurrency::TRY;
    static protected $user_country          = OtelzCountry::XX;
    static protected $decimal_digit_number  = 2;
    static protected $device_type           = OtelzDevice::UNKNOWN;
    static protected $page_number           = 1;
    static protected $page_size             = 15;

    public static function lang($lang)
    {
        self::$lang = $lang;
        return new static;
    }

    public static function country($country)
    {
        self::$country = $country;
        return new static;
    }

    public static function cityReference($city_reference)
    {
        self::$city_reference = $city_reference;
        return new static;
    }

    public static function districtReference($district_reference)
    {
        self::$district_reference = $district_reference;
        return new static;
    }

    public static function facilityReference($facility_reference)
    {
        self::$facility_reference = $facility_reference;
        return new static;
    }

    public static function filter($filter) {
        self::$filter = $filter;
        return new static;
    }

    public static function latitude($latitude) {
        self::$latitude = $latitude;
        return new static;
    }

    public static function longitude($longitude) {
        self::$longitude = $longitude;
        return new static;
    }

    public static function distance($distance) {
        self::$distance = $distance;
        return new static;
    }

    public static function type($type) {
        self::$type = $type;
        return new static;
    }

    public static function startDate($startDate) {
        self::$startDate = $startDate;
        return new static;
    }

    public static function endDate($endDate) {
        self::$endDate = $endDate;
        return new static;
    }

    public static function party($party) {
        self::$party = $party;
        return new static;
    }

    public static function adults($adults) {
        self::$adults = $adults;
        return new static;
    }

    public static function children($children) {
        self::$children = $children;
        return new static;
    }

    public static function currency($currency) {
        self::$currency = $currency;
        return new static;
    }

    public static function userCountry($user_country) {
        self::$user_country = $user_country;
        return new static;
    }

    public static function decimalDigitNumber($decimal_digit_number) {
        self::$decimal_digit_number = $decimal_digit_number;
        return new static;
    }

    public static function deviceType($device_type) {
        self::$device_type = $device_type;
        return new static;
    }


    public static function addFacilityReferences(int $facility_references) {
        self::$facility_references[] = $facility_references;
        return new static;
    }

    public static function facilityReferences(array $facility_references) {
        self::$facility_references = $facility_references;
        return new static;
    }

    public static function pageNumber($page_number) {
        self::$page_number = $page_number;
        return new static;
    }

    public static function pageSize($page_size) {
        self::$page_size = $page_size;
        return new static;
    }

    static function list() {
        if(empty(self::$filter)){
            self::$filter = self::validateFilter();
        }

        $res = Otelz::post("/v2/search/availability", [
            "lang"                  => self::$lang,
            "start_date"            => self::$startDate,
            "end_date"              => self::$endDate,
            "currency"              => self::$currency,
            "filter"                => (object) self::$filter,
            "price_formatter"   => [
                "decimal_digit_number"  => self::$decimal_digit_number,
            ],
            "party" => [
                "adults"    => self::$adults,
                "children"  => self::$children
            ],
            "user_country"      => self::$user_country,
            "page_number"       => self::$page_number,
            "page_size"         => self::$page_size,
        ]);

        if(!empty($res["errors"])){
            Otelz::error($res["errors"][0]["message"], $res["errors"][0]["code"]);
        }

        if($res->status() != 200){
            Otelz::error("Oh no!, status code: " . $res->status(), $res->status());
        }

       return Otelz::data("Hotels listed.", $res->json());
    }

    static function data() {
        $res = Otelz::post("/data/hotel_data/", [
            "lang"                  => self::$lang,
            "country"               => self::$country,
            "city_reference"        => self::$city_reference,
            "district_reference"    => self::$district_reference,
            "facility_reference"    => self::$facility_reference,
        ]);

        $resJson = $res->json();
        if(!empty($resJson["errors"])){
            return Otelz::error( $resJson["errors"][0]["message"], $resJson["errors"][0]["code"]);
        }

        if($res->status() != 200){
            Otelz::errorThrow("Oh no!, status code: " . $res->status(), $res->status());
        }

       return Otelz::data("Hotels listed.", $resJson);
    }


    static function validateFilter() {
        if(empty(self::$filter)){
            if(empty(self::$type)){
                if(!empty(self::$facility_references)){
                    return self::getHotelIdList();
                }else if (!empty(self::$latitude)){
                    return self::getGeolocation();
                }else{
                    return;
                }
            }else{
                if(self::$type == "HotelIdList"){
                    return self::getHotelIdList();
                }else if (self::$type == "Geolocation"){
                    return self::getGeolocation();
                }else{
                    return;
                }
            }
        }
    }

    static function getHotelIdList() {
        return [
            "type"                  => "HotelIdList",
            "facility_references"   => self::$facility_references,
        ];
    }

    static function getGeolocation() {
        return [
            "type"       => "Geolocation",
            "latitude"   => self::$latitude,
            "longitude"  => self::$longitude,
            "distance"   => self::$distance,
        ];
    }
}
