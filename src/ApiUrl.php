<?php

namespace DanielS\Tankerkoenig;

class ApiUrl
{
    public string $baseUri = 'https://creativecommons.tankerkoenig.de/json/';
    private string $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @return string
     */
    public function getBaseUri(): string
    {
        return $this->baseUri;
    }

    /**
     * @param float  $lat
     * @param float  $lng
     * @param float  $radius
     * @param string $sort
     * @param string $type
     *
     * @return string
     */
    public function getListUrl(float $lat, float $lng, float $radius, string $sort, string $type): string
    {
        $query = http_build_query(
            [
                'lat'   => $lat,
                'lng'   => $lng,
                'rad'   => $radius,
                'sort'  => $sort,
                'type'  => $type,
                'apikey'=> $this->apiKey,
            ]
        );
        return "list.php?$query";
    }

    /**
     * @param $stationId
     *
     * @return string
     */
    public function getStationDetailUrl($stationId): string
    {
        $query = http_build_query(
            [
                'id'    => $stationId,
                'apikey'=> $this->apiKey,
            ]
        );
        return "detail.php?$query";
    }

    /**
     * @param array $stationList
     *
     * @return string
     * @throws ApiException
     */
    public function getPricesUrl(array $stationList): string
    {
        if (count($stationList) < 1 || count($stationList) > 10) {
            throw new ApiException('Preisabfrage darf nur zwischen 1 und 10 Stationen beinhalten');
        }

        $query = http_build_query(
            [
                'ids'   => implode(',', $stationList),
                'apikey'=> $this->apiKey,
            ]
        );

        return "prices.php?$query";
    }

    /**
     * @return string
     */
    public function getComplaintUrl(): string
    {
        $query = http_build_query(['apikey'=> $this->apiKey]);

        return $this->baseUri . "complaint.php?$query";
    }
}
