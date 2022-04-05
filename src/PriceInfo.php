<?php

namespace DanielS\Tankerkoenig;

class PriceInfo
{
	public string $stationId;
	public string $status;
	public float|null $e5;
	public float|null $e10;
	public float|null $diesel;

	public static function fromApiArray(array $array) : self
    {
		return new static(
			$array['stationId'],
			$array['status'],
			$array[ApiClient::TYPE_E5] ?? null,
			$array[ApiClient::TYPE_E10] ?? null,
			$array[ApiClient::TYPE_DIESEL] ?? null
		);
	}

	public function __construct(
		string $stationId,
		string $status,
		float|null $e5,
		float|null $e10,
		float|null $diesel
	) {
		$this->stationId = $stationId;
		$this->status = $status;
		$this->e5     = $e5;
		$this->e10 = $e10;
		$this->diesel = $diesel;
	}
}