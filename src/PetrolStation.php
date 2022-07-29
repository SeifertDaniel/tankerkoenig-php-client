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

/**
 * Class PetrolStation
 *
 * Simple wrapper to hold petrol station data in a handy object
 *
 * @package DanielS\Tankerkoenig
 */
/** @phpstan-consistent-constructor */
class PetrolStation
{
    public string $id;
    public string $name;
    public string $brand;
    public string $street;
    public string $houseNumber;
    public string $postCode;
    public string $place;
    /** @var array<String>  */
    public array $openingTimes;
    /** @var array<String>  */
    public array $overrides;
    public bool $wholeDay;
    public bool $isOpen;

    public float $e5;
    public float $e10;
    public float $diesel;
    public float $lat;
    public float $lng;
    public string|null $state;

    /**
     * @param array<String> $array
     * @return static
     */
    public static function fromApiArray(array $array): self
    {
        return new static(
            $array['id'],
            $array['name'],
            $array['brand'],
            $array['street'],
            $array['houseNumber'],
            $array['postCode'],
            $array['place'],
            $array['openingTimes'],
            $array['overrides'],
            $array['wholeDay'],
            $array['isOpen'],
            $array['e5'],
            $array['e10'],
            $array['diesel'],
            $array['lat'],
            $array['lng'],
            $array['state']
        );
    }

    /**
     * @param string $id
     * @param string $name
     * @param string $brand
     * @param string $street
     * @param string $houseNumber
     * @param string $postCode
     * @param string $place
     * @param array<String> $openingTimes
     * @param array<String> $overrides
     * @param bool $wholeDay
     * @param bool $isOpen
     * @param float $e5
     * @param float $e10
     * @param float $diesel
     * @param float $lat
     * @param float $lng
     * @param string|null $state
     */
    public function __construct(
        string $id,
        string $name,
        string $brand,
        string $street,
        string $houseNumber,
        string $postCode,
        string $place,
        array $openingTimes,
        array $overrides,
        bool $wholeDay,
        bool $isOpen,
        float $e5,
        float $e10,
        float $diesel,
        float $lat,
        float $lng,
        string $state = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->brand = $brand;
        $this->street = $street;
        $this->houseNumber = $houseNumber;
        $this->postCode = $postCode;
        $this->place = $place;
        $this->openingTimes = $openingTimes;
        $this->overrides = $overrides;
        $this->wholeDay = $wholeDay;
        $this->isOpen = $isOpen;
        $this->e5 = $e5;
        $this->e10 = $e10;
        $this->diesel = $diesel;
        $this->lat = $lat;
        $this->lng = $lng;
        $this->state = $state;
    }
}
