<?php

namespace Lang\Tankerkoenig;

class PriceInfo
{
	public string $stationId;
	public string $status;
	public float $e5;
	public float $e10;
	public float $diesel;

	public static function fromApiArray(array $array) : self
    {
		return new static(
			$array['stationId'],
			$array['status'],
			$array[ApiClient::TYPE_E5],
			$array[ApiClient::TYPE_E10],
			$array[ApiClient::TYPE_DIESEL]
		);
	}

	public function __construct(
		string $stationId,
		string $status,
		float $e5,
		float $e10,
		float $diesel
	) {
		$this->stationId = $stationId;
		$this->status = $status;
		$this->e5     = $e5;
		$this->e10 = $e10;
		$this->diesel = $diesel;
	}
}