<?php
namespace Lang\Tankerkoenig;

class ApiClient
{
	const SORT_PRICE    = 'price';
	const SORT_DIST     = 'dist';

	const TYPE_E10      = 'e10';
	const TYPE_E5       = 'e5';
	const TYPE_DIESEL   = 'diesel';
	const TYPE_ALL      = 'all';

	private $apiKey;

    public function __construct(string $apiKey)
    {
		$this->apiKey = $apiKey;
	}

	public function search(float $lat, float $lng, string $type = self::TYPE_DIESEL, int $radius = 5, string $sort = self::SORT_DIST): array
    {
        $apiUrl = new ApiUrl($this->apiKey);
		$json = file_get_contents($apiUrl->getListUrl($lat, $lng, $radius, $sort, $type));

		if ($json === false) {
		    throw new ApiException("FEHLER - Die Tankerkoenig-API konnte nicht abgefragt werden!");
		}

		/** @var \stdClass $data */
		$data = json_decode($json);

        if ($data->ok !== true) {
            throw new ApiException("FEHLER - Die Tankerkoenig-API meldet diesen Fehler: ".$data->message);
        }

		$result = [];
		
		foreach ($data->stations as $station) {
            $prices = $type === self::TYPE_ALL ?
                [
                    self::TYPE_E5 => (float)($station->e5),
                    self::TYPE_E10 => (float)($station->e10),
                    self::TYPE_DIESEL => (float)($station->diesel)
                ] : [
                    'price' => (float)($station->price)
                ];

			$result[$station->id] = array_merge([
				'name' => ($station->name),
				'brand' => ($station->brand),
				'dist' => (float)($station->dist),
				'street' => ($station->street),
				'houseNumber' => ($station->houseNumber),
				'postCode' => ($station->postCode),
				'place' => ($station->place),
			], $prices);
		}

		return $result;
	}

	public function detail(string $gasStationId) : GasStation
    {
        $apiUrl = new ApiUrl($this->apiKey);
		$json = file_get_contents($apiUrl->getStationDetailUrl($gasStationId));

		if ($json === false) {
		    throw new ApiException("FEHLER - Die Tankerkoenig-API konnte nicht abgefragt werden!");
		}

        /** @var array $data */
		$data = json_decode($json, true);

        if ($data['ok'] !== true) {
            throw new ApiException("FEHLER - Die Tankerkoenig-API meldet diesen Fehler: ".$data['message']);
        }

		return GasStation::fromApiArray($data['station']);
	}
}