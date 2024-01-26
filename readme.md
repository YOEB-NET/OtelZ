# OtelZ Laravel Packet

### Models:
    OtelzHotel::class // Otel arama.
    OtelzInstallment::class // Banakaların taksit imkanları..
    OtelzPlace::class // Şehirler ve ilçeler.
    OtelzReservation::class // Rezarvasyon ve satın alma.

Bu paketteki isimlendirmeler https://connect.otelz.com/ esas alınarak yapılmıştır küçük farklılıklar olabilir.

## Hotel Availability (OtelzHotel)
**Functions:**
	

Tablodaki fonksiyon isimleri şu şekildedir:

    - lang()
    - country()
    - cityReference()
    - districtReference()
    - facilityReference()
    - filter()
    - latitude()
    - longitude()
    - distance()
    - type()
    - startDate()
    - endDate()
    - party()
    - adults()
    - children()
    - currency()
    - userCountry()
    - decimalDigitNumber()
    - deviceType()
    - addFacilityReferences()
    - facilityReferences()
    - pageNumber() // Sayfa sayısı.
    - pageSize() // 1 sayfada kaç adet otel gelecek.
    - list() // v2/search/availability istek atar.
    - data() // data/hotel_data istek atar
    

**Example:**

    $hotel = OtelzHotel::pageNumber($page)
    ->currency($currency)
    ->endDate($end_date)
    ->startDate($start_date)
    ->userCountry($user_country)
    ->latitude($latitude)
    ->longitude($longitude)
    ->distance($distance)
    ->facilityReferences($facility_references)
    ->adults($adults)
    ->list();
