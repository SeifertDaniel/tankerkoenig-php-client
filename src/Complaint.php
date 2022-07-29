<?php

/**
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) 2017 Tobias Lang
 * @copyright Copyright (c) 2022-present Daniel Seifert <git@daniel-seifert.com>
 */

declare(strict_types=1);

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

    /** @var array|string[] */
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
     * @param string $type
     *
     * @return bool
     */
    public function isCorrectionRequired(string $type): bool
    {
        return in_array($type, $this->correctionRequiredTypes);
    }
}
