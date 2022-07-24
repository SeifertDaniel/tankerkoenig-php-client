<?php

namespace DanielS\Tankerkoenig;

class Complaint
{
    public const WRONG_PETROL_STATION_NAME     = 'wrongPetrolStationName';
    public const WRONG_STATUS_OPEN             = 'wrongStatusOpen';
    public const WRONG_STATUS_CLOSED           = 'wrongStatusClosed';
    public const WRONG_PRICE_E5                = 'wrongPriceE5';
    public const WRONG_PRICE_E10               = 'wrongPriceE10';
    public const WRONG_PRICE_DIESEL            = 'wrongPriceDiesel';
    public const WRONG_PETROL_STATION_BRAND    = 'wrongPetrolStationBrand';
    public const WRONG_PETROL_STATION_STREET   = 'wrongPetrolStationStreet';
    public const WRONG_PETROL_STATION_HOUSENUMBER = 'wrongPetrolStationHouseNumber';
    public const WRONG_PETROL_STATION_POSTCODE = 'wrongPetrolStationPostcode';
    public const WRONG_PETROL_STATION_PLACE    = 'wrongPetrolStationPlace';
    public const WRONG_PETROL_STATION_LOCATION = 'wrongPetrolStationLocation';

    public array $correctionRequiredTypes = [
        self::WRONG_PETROL_STATION_NAME,
        self::WRONG_PRICE_E5,
        self::WRONG_PRICE_E10,
        self::WRONG_PRICE_DIESEL,
        self::WRONG_PETROL_STATION_BRAND,
        self::WRONG_PETROL_STATION_STREET,
        self::WRONG_PETROL_STATION_HOUSENUMBER,
        self::WRONG_PETROL_STATION_POSTCODE,
        self::WRONG_PETROL_STATION_PLACE,
        self::WRONG_PETROL_STATION_LOCATION,
    ];

    /**
     * @param $type
     *
     * @return bool
     */
    public function isCorrectionRequired($type): bool
    {
        return in_array($type, $this->correctionRequiredTypes);
    }
}
