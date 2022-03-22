<?php
namespace Lang\Tankerkoenig;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

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

    /**
     * @param float $lat
     * @param float $lng
     * @param string $type
     * @param int $radius
     * @param string $sort
     * @return array
     * @throws ApiException
     */
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

    /**
     * @param string $gasStationId
     * @return GasStation
     * @throws ApiException
     */
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

    /**
     * @param array $stationList
     *
     * @return array
     * @throws ApiException
     */
	public function prices(array $stationList)
    {
        $apiUrl = new ApiUrl($this->apiKey);
        $json = file_get_contents($apiUrl->getPricesUrl($stationList));

        if ($json === false) {
            throw new ApiException("FEHLER - Die Tankerkoenig-API konnte nicht abgefragt werden!");
        }

        /** @var array $data */
        $data = json_decode($json, true);

        if ($data['ok'] !== true) {
            throw new ApiException("FEHLER - Die Tankerkoenig-API meldet diesen Fehler: ".$data['message']);
        }

        $result = [];

        foreach ($data['prices'] as $stationId => $priceinfo) {
            $result[$stationId] = PriceInfo::fromApiArray(
                array_merge(
                    ['stationId'    => $stationId],
                    $priceinfo
                )
            );
        }

        return $result;
    }

    /**
     * @param $stationId
     * @param $type
     * @param $correction
     * @return bool
     * @throws ApiException
     * @throws GuzzleException
     */
    public function complaint($stationId, $type, $correction = null)
    {
        $apiUrl = new ApiUrl($this->apiKey);

        $complaint = new Complaint();
        if ($complaint->isCorrectionRequired($type) && !$correction) {
            throw new ApiException("FEHLER - Der Korrekturtyp '".$type."' erfordert die Angabe eines Korrekturwertes.");
        }

        $body = [
            'apikey'     => $this->apiKey,
            'id'         => $stationId,
            'type'       => $type,
            'correction' => $correction
        ];

        $client = new Client();
        $response = $client->request(
            'POST',
            $apiUrl->getComplaintUrl(),
            [
                'curl'  => [
                    CURLOPT_RETURNTRANSFER  => false,
                    CURLOPT_SSL_VERIFYPEER  => false,
                ],
                'form_params' => $body
            ]
        );

        $json = $response->getBody();

        if ($response->getStatusCode() !== 200 || $json === false) {
            throw new ApiException("FEHLER - Die Tankerkoenig-API konnte nicht abgefragt werden!");
        }

        $data = json_decode($json, true);

        if ($data['ok'] !== true) {
            throw new ApiException("FEHLER - Die Tankerkoenig-API meldet diesen Fehler: " . $data['message']);
        }

        return true;
    }
}