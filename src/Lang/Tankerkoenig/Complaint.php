<?php

namespace Lang\Tankerkoenig;

class Complaint
{
    const WRONG_PETROL_STATION_NAME     = 'wrongPetrolStationName';
    const WRONG_STATUS_OPEN             = 'wrongStatusOpen';
    const WRONG_STATUS_CLOSED           = 'wrongStatusClosed';
    const WRONG_PRICE_E5                = 'wrongPriceE5';
    const WRONG_PRICE_E10               = 'wrongPriceE10';
    const WRONG_PRICE_DIESEL            = 'wrongPriceDiesel';
    const WRONG_PETROL_STATION_BRAND    = 'wrongPetrolStationBrand';
    const WRONG_PETROL_STATION_STREET   = 'wrongPetrolStationStreet';
    const WRONG_PETROL_STATION_HOUSENUMBER = 'wrongPetrolStationHouseNumber';
    const WRONG_PETROL_STATION_POSTCODE = 'wrongPetrolStationPostcode';
    const WRONG_PETROL_STATION_PLACE    = 'wrongPetrolStationPlace';
    const WRONG_PETROL_STATION_LOCATION = 'wrongPetrolStationLocation';

    public $correctionRequiredTypes = [
        self::WRONG_PETROL_STATION_NAME,
        self::WRONG_PRICE_E5,
        self::WRONG_PRICE_E10,
        self::WRONG_PRICE_DIESEL,
        self::WRONG_PETROL_STATION_BRAND,
        self::WRONG_PETROL_STATION_STREET,
        self::WRONG_PETROL_STATION_HOUSENUMBER,
        self::WRONG_PETROL_STATION_POSTCODE,
        self::WRONG_PETROL_STATION_PLACE,
        self::WRONG_PETROL_STATION_LOCATION
    ];

    public function isCorrectionRequired($type)
    {
        return in_array($type, $this->correctionRequiredTypes);
    }
}