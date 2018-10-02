Expedia-PHP-API - v3 DEPRECATED since 2019
===============

PHP Wrapper for Expedia API

Official API documentation can be found here: http://developer.ean.com/docs/

For usage examples please take a look at source code of `example.php` file.

Usage
======

Use Jkhaled\Expedia\Expedia class to send built request

```php
$expedia = new \Jkhaled\Expedia\Expedia($api, $secret, $cid); 

$expedia->setCustomerIpAddress($_SERVER['REMOTE_ADDR']);
$expedia->setCustomerUserAgent($_SERVER['HTTP_USER_AGENT']);
$expedia->setCustomerSessionId(session_id());
$expedia->setCurrencyCode('USD');
$expedia->setRev(26);

$expedia->setRequest((new \Jkhaled\Expedia\Request\HotelList($options))); // setRequest(RequestInterface $request)
$expedia->send(); // return response
```

Get Hotel list based on search criteria

```php

$options = [
    'date_in' => '01/10/2018',
    'date_out' => '01/10/2018',
    'number_of_result' => 20,
    'rooms' => '',
    'include_details' => true,
    'include_hotel_fee_breakdown' => false,
    'city_code' => 'Paris',
    'country_code' => 'FR'
];

$hotelList = new Jkhaled\Expedia\Request\HotelList($options);
$xml = $hotelList->prepareRequest();
print $xml;
//result
/*
<HotelListRequest>
    <apiExperience>PARTNER_AFFILIATE</apiExperience>
    <arrivalDate>01/10/2018</arrivalDate>
    <departureDate>01/10/2018</departureDate>
    <numberOfResults>20</numberOfResults>
    <RoomGroup>
        <Room>
            <numberOfAdults>2</numberOfAdults>
            <numberOfChildren>0</numberOfChildren>
        </Room>
        <Room>
            <numberOfAdults>2</numberOfAdults>
            <numberOfChildren>2</numberOfChildren>
            <childAges>2</childAges>
            <childAges>1</childAges>
        </Room>
    </RoomGroup>
    <includeDetails>true</includeDetails>
    <includeHotelFeeBreakdown>true</includeHotelFeeBreakdown>
    <city>Paris</city>
    <countryCode>FR</countryCode>
</HotelListRequest>
*/
``` 
