<?php

namespace DanielS\Tankerkoenig;

/**
 * Class PetrolStation
 *
 * Simple wrapper to hold petrol station data in a handy object
 *
 * @package DanielS\Tankerkoenig
 */
class PetrolStation {

	public string $id;
	public string $name;
	public string $brand;
	public string $street;
	public string $houseNumber;
	public string $postCode;
	public string $place;
	public array $openingTimes;
	public array $overrides;
	public bool $wholeDay;
	public bool $isOpen;

	public float $e5;
	public float $e10;
	public float $diesel;
	public float $lat;
	public float $lng;
	public string $state;

	public static function fromApiArray(array $array) : self {
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
		$state
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