<?php

namespace DanielS\Tankerkoenig\Tests\DataProviders;

use DanielS\Tankerkoenig\Tests\ApiTestCase;
use stdClass;

class ApiClient
{
    /** @var ApiTestCase */
    public ApiTestCase $test;

    public function __construct(ApiTestCase $test)
    {
        $this->test = $test;
    }

    public function getNotOkArrayResponse(): array
    {
        $data = [];
        $data['ok'] = false;
        $data['message'] = 'unvalid';
        return $data;
    }

    public function getNotOkObjectResponse(): stdClass
    {
        $data = new stdClass();
        $data->ok = false;
        $data->message = 'unvalid';
        return $data;
    }

    public function getSinglePriceResponse(): stdClass
    {
        $data = new stdClass();
        $data->ok = true;
        $data->stations = [];
        $station = new stdClass();
        $station->id = 'stationId';
        $station->price = 1.85;
        $station->name = 'stationName';
        $station->brand = 'stationBrand';
        $station->dist = 4.38;
        $station->street = 'stationStreet';
        $station->houseNumber = 'stationNumber';
        $station->postCode = 'stationPostCode';
        $station->place = 'stationPlace';
        $data->stations[] = $station;
        return $data;
    }

    public function getExpectedSinglePriceResponse(): array
    {
        return [
            'stationId' => [
                'name'  => 'stationName',
                'brand' => 'stationBrand',
                'dist'  => 4.38,
                'street'=> 'stationStreet',
                'houseNumber'   => 'stationNumber',
                'postCode'  => 'stationPostCode',
                'place' => 'stationPlace',
                'price' => 1.85
            ]
        ];
    }

    public function getAllPricesResponse(): stdClass
    {
        $data = new stdClass();
        $data->ok = true;
        $data->stations = [];
        $station = new stdClass();
        $station->id = 'stationId';
        $station->e5 = 1.90;
        $station->e10 = 1.85;
        $station->diesel = 1.80;
        $station->name = 'stationName';
        $station->brand = 'stationBrand';
        $station->dist = 4.38;
        $station->street = 'stationStreet';
        $station->houseNumber = 'stationNumber';
        $station->postCode = 'stationPostCode';
        $station->place = 'stationPlace';
        $data->stations[] = $station;
        return $data;
    }

    public function getExpectedAllPricesResponse(): array
    {
        return [
            'stationId' => [
                'name'  => 'stationName',
                'brand' => 'stationBrand',
                'dist'  => 4.38,
                'street'=> 'stationStreet',
                'houseNumber'   => 'stationNumber',
                'postCode'  => 'stationPostCode',
                'place' => 'stationPlace',
                'e5' => 1.90,
                'e10' => 1.85,
                'diesel' => 1.80,
            ]
        ];
    }

    public function getStationDetailResponse(): array
    {
        $data = [];
        $data['ok'] = true;
        $station = [];
        $station['id'] = 'stationId';
        $station['e5'] = 1.90;
        $station['e10'] = 1.85;
        $station['diesel'] = 1.80;
        $station['name'] = 'stationName';
        $station['brand'] = 'stationBrand';
        $station['street'] = 'stationStreet';
        $station['houseNumber'] = 'stationNumber';
        $station['postCode'] = 'stationPostCode';
        $station['place'] = 'stationPlace';
        $station['openingTimes'] = ['stationOpeningTimes'];
        $station['overrides'] = ['stationOverrides'];
        $station['wholeDay'] = 'stationWholeDay';
        $station['isOpen'] = 'stationIsOpen';
        $station['lat'] = 52.521;
        $station['lng'] = 13.413;
        $station['state'] = 'stationState';
        $data['station'] = $station;
        return $data;
    }

    public function getPricesResponse(): array
    {
        $data = [];
        $data['ok'] = true;
        $priceitem = [];
        $priceitem['stationId'] = 'stationId';
        $priceitem['e5'] = 1.90;
        $priceitem['e10'] = 1.85;
        $priceitem['diesel'] = 1.80;
        $priceitem['status'] = 'stationStatus';
        $prices = ['stationId1' => $priceitem, 'stationId2' => $priceitem];
        $data['prices'] = $prices;
        return $data;
    }

    public function getPricesNoStationsResponse(): array
    {
        $data = [];
        $data['ok'] = true;
        $prices = ['xxx' => 'no_stations'];
        $data['prices'] = $prices;
        return $data;
    }
}