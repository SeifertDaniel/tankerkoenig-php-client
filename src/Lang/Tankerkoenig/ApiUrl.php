<?php

namespace Lang\Tankerkoenig;

class ApiUrl
{
    public $baseUrl = 'https://creativecommons.tankerkoenig.de/json/';
    private $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @param $lat
     * @param $lng
     * @param $radius
     * @param $sort
     * @param $type
     *
     * @return string
     */
    public function getListUrl($lat, $lng, $radius, $sort, $type)
    {
        return $this->baseUrl."list.php?lat={$lat}&lng={$lng}&rad={$radius}&sort={$sort}&type={$type}&apikey={$this->apiKey}";
    }

    /**
     * @param $gasStationId
     *
     * @return string
     */
    public function getStationDetailUrl($gasStationId)
    {
        return $this->baseUrl."detail.php?id={$gasStationId}&apikey={$this->apiKey}";
    }

    public function getPricesUrl(array $stationList)
    {
        if (count($stationList) < 1 || count($stationList) > 10) {
            throw new ApiException('Preisabfrage darf nur zwischen 1 und 10 Stationen beinhalten');
        }

        return $this->baseUrl."prices.php?ids=".implode(',', $stationList)."&apikey={$this->apiKey}";
    }

    /**
     * @return string
     */
    public function getComplaintUrl()
    {
        return $this->baseUrl."complaint.php?apikey={$this->apiKey}";
    }
}