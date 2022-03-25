<?php

namespace DanielS\Tankerkoenig\Tests\DataProviders;

use DanielS\Tankerkoenig\GasStation;
use DanielS\Tankerkoenig\PriceInfo;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use stdClass;

class ApiClient
{
    /** @var TestCase */
    public $test;

    public function __construct(TestCase $test)
    {
        $this->test = $test;
    }

    public function getNotOkArrayResponse()
    {
        $data = [];
        $data['ok'] = false;
        $data['message'] = 'unvalid';
        return $data;
    }

    public function getNotOkObjectResponse()
    {
        $data = new stdClass();
        $data->ok = false;
        $data->message = 'unvalid';
        return $data;
    }

    public function getSinglePriceResponse()
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

    public function getExpectedSinglePriceResponse()
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

    public function getAllPricesResponse()
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

    public function getExpectedAllPricesResponse()
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

    public function getStationDetailResponse()
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

    public function getPricesResponse()
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
}