<?php

namespace DanielS\Tankerkoenig;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use stdClass;

class ApiClient
{
    public const SORT_PRICE    = 'price';
    public const SORT_DIST     = 'dist';

    public const TYPE_E10      = 'e10';
    public const TYPE_E5       = 'e5';
    public const TYPE_DIESEL   = 'diesel';
    public const TYPE_ALL      = 'all';

    private string $apiKey;
    public ApiUrl $apiUrl;
    public Complaint $complaint;
    public Client $requestClient;

    public function __construct(string $apiKey, ApiUrl $apiUrl = null, Complaint $complaint = null, Client $client = null)
    {
        $this->apiKey = $apiKey;
        $this->apiUrl = $apiUrl ?: new ApiUrl($this->apiKey);
        $this->complaint = $complaint ?: new Complaint();
        $this->requestClient = $client ?: new Client([ 'base_uri' => $this->apiUrl->getBaseUri() ]);
    }

    /**
     * @param float  $lat
     * @param float  $lng
     * @param string $type
     * @param int    $radius
     * @param string $sort
     *
     * @return array<int|string, string[]|mixed[]>
     * @throws ApiException
     * @throws GuzzleException
     */
    public function search(
        float $lat,
        float $lng,
        string $type = self::TYPE_DIESEL,
        int $radius = 5,
        string $sort = self::SORT_DIST
    ): array {
        $json = $this->request($this->apiUrl->getListUrl($lat, $lng, $radius, $sort, $type));
        $data = $this->decodeResponse($json);

        $result = [];

        foreach ($data->stations as $station) {
            $prices = $type === self::TYPE_ALL ?
                [
                    self::TYPE_E5 => (float)($station->e5),
                    self::TYPE_E10 => (float)($station->e10),
                    self::TYPE_DIESEL => (float)($station->diesel),
                ] : [
                    'price' => (float)($station->price),
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
     * @param string $stationId
     *
     * @return PetrolStation
     * @throws ApiException
     * @throws GuzzleException
     */
    public function detail(string $stationId): PetrolStation
    {
        $json = $this->request($this->apiUrl->getStationDetailUrl($stationId));
        $data = $this->decodeResponse($json, true);

        return PetrolStation::fromApiArray($data['station']);
    }

    /**
     * @param array<String> $stationList
     *
     * @return array<PriceInfo>
     * @throws ApiException
     * @throws GuzzleException
     */
    public function prices(array $stationList): array
    {
        $url = $this->apiUrl->getPricesUrl($stationList);
        $json = $this->request($url);
        $data = $this->decodeResponse($json, true);

        if (in_array('xxx', array_keys($data['prices']))) {
            throw new ApiException('request '.$url.' returns no found stations');
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
     * @param string $stationId
     * @param string $type
     * @param null $correction
     * @return bool
     * @throws ApiException
     * @throws GuzzleException
     */
    public function complaint(string $stationId, string $type, $correction = null): bool
    {
        $this->checkForMissingCorrection($type, $correction);

        $form_params = [
            'apikey'     => $this->apiKey,
            'id'         => $stationId,
            'type'       => $type,
            'correction' => $correction,
        ];

        $json = $this->request($this->apiUrl->getComplaintUrl(), 'POST', $form_params);
        $this->decodeResponse($json);

        return true;
    }

    /**
     * @param string $url
     * @param string $method
     * @param array<string, string|null> $postArgs
     *
     * @return string
     * @throws ApiException
     * @throws GuzzleException
     */
    protected function request(string $url, string $method = 'GET', array $postArgs = []): string
    {
        $response = $this->requestClient->request(
            $method,
            $url,
            [
                'curl' => [
                    CURLOPT_SSL_VERIFYPEER => false,
                ],
                'form_params' => $postArgs,
            ]
        );

        if ($response->getStatusCode() != 200) {
            throw new ApiException('request '.$url.' returns status code '.$response->getStatusCode());
        }

        return (string) $response->getBody();
    }

    /**
     * @param string $json
     * @param bool   $associative
     *
     * @return stdClass|array<string, float|int|string>
     * @throws ApiException
     */
    protected function decodeResponse(string $json, bool $associative = false): array|stdClass
    {
        if (strlen($json) === 0) {
            throw new ApiException("FEHLER - Die Tankerkoenig-API konnte nicht abgefragt werden!");
        }

        /** @var stdClass|array<string, string|float|int> $data */
        $data = json_decode($json, $associative);
        $isObject = is_object($data);

        if (true !== ($isObject ? $data->ok : $data['ok'])) {
            $message = $isObject ? $data->message : $data['message'];
            throw new ApiException("FEHLER - Die Tankerkoenig-API meldet diesen Fehler: " . $message);
        }

        return $data;
    }

    /**
     * @param string $type
     * @param mixed $correction
     *
     * @throws ApiException
     */
    protected function checkForMissingCorrection(string $type, mixed $correction): void
    {
        if ($this->complaint->isCorrectionRequired($type) && ! $correction) {
            throw new ApiException("FEHLER - Der Korrekturtyp '" . $type . "' erfordert die Angabe eines Korrekturwertes.");
        }
    }
}
