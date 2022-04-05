<?php

namespace DanielS\Tankerkoenig\Tests;

use DanielS\Tankerkoenig\ApiClient;
use DanielS\Tankerkoenig\ApiException;
use DanielS\Tankerkoenig\ApiUrl;
use DanielS\Tankerkoenig\Complaint;
use DanielS\Tankerkoenig\PetrolStation;
use DanielS\Tankerkoenig\PriceInfo;
use DanielS\Tankerkoenig\Tests\DataProviders\ApiClient as ApiClientDataProvider;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\BufferStream;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\MockObject;
use ReflectionException;
use stdClass;

class ApiClientTest extends ApiTestCase
{
    public string $fixtureApiKey = 'fixtureApiKey';
    /** @var ApiClient */
    public ApiClient $api;
    public string $jsonFixture = 'json_content';

    public function setUp():void
    {
        parent::setUp();

        $this->api = new ApiClient($this->fixtureApiKey);
    }

    /**
     * @test
     * @covers ApiClient::__construct()
     * @return void
     * @throws ReflectionException
     */
    public function testConstructNotInjected()
    {
        $this->assertSame(
            $this->fixtureApiKey,
            $this->getValue(
                $this->api,
                'apiKey'
            )
        );

        $this->assertInstanceOf(
            ApiUrl::class,
            $this->api->apiUrl
        );

        $this->assertInstanceOf(
            Complaint::class,
            $this->api->complaint
        );

        $this->assertInstanceOf(
            Client::class,
            $this->api->requestClient
        );
    }

    /**
     * @test
     * @covers ApiClient::__construct()
     * @return void
     */
    public function testConstructInjectedApiUrl()
    {
        /** @var MockObject|ApiUrl $apiUrlMock */
        $apiUrlMock = $this->getMockBuilder(ApiUrl::class)
                           ->onlyMethods(['getBaseUri'])
                           ->setConstructorArgs([$this->fixtureApiKey])
                           ->getMock();
        $apiUrlMock->expects($this->once())->method('getBaseUri')->willReturn('baseUriFixture');

        /** @var MockObject|ApiClient $apiClientMock */
        $apiClientMock = $this->getMockBuilder( ApiClient::class )
              ->setConstructorArgs( [
                  $this->fixtureApiKey,
                  $apiUrlMock
                ] )
              ->getMock();

        $this->api = $apiClientMock;

        $this->assertSame(
            $apiUrlMock,
            $this->api->apiUrl
        );
    }

    /**
     * @test
     * @covers ApiClient::__construct()
     * @return void
     */
    public function testConstructInjected()
    {
        /** @var MockObject|ApiUrl $apiUrlMock */
        $apiUrlMock = $this->getMockBuilder(ApiUrl::class)
                           ->setConstructorArgs([$this->fixtureApiKey])
                           ->getMock();

        /** @var Complaint|MockObject $complaintMock */
        $complaintMock = $this->getMockBuilder(Complaint::class)->getMock();

        /** @var MockObject|Client $requestClientMock */
        $requestClientMock = $this->getMockBuilder(Client::class)->getMock();

        /** @var MockObject|ApiClient $apiClientMock */
        $apiClientMock = $this->getMockBuilder( ApiClient::class )
              ->setConstructorArgs( [
                  $this->fixtureApiKey,
                  $apiUrlMock,
                  $complaintMock,
                  $requestClientMock
                ] )
              ->getMock();

        $this->api = $apiClientMock;

        $this->assertSame(
            $apiUrlMock,
            $this->api->apiUrl
        );

        $this->assertSame(
            $complaintMock,
            $this->api->complaint
        );

        $this->assertSame(
            $requestClientMock,
            $this->api->requestClient
        );
    }

    /**
     * @test
     * @covers ApiClient::search()
     * @dataProvider searchDataProvider
     *
     * @return void
     * @throws ReflectionException
     */
    public function testSearch($responseContent, $fuelType, $cantRequest, $cantDecode, $expected = null)
    {
        $lat = 52.521;
        $lng = 13.413;
        $ftype = $fuelType;
        $radius = 10;
        $sort = ApiClient::SORT_PRICE;

        /** @var MockObject|ApiUrl $apiUrlMock */
        $apiUrlMock = $this->getMockBuilder(ApiUrl::class)
                           ->onlyMethods(['getListUrl'])
                           ->setConstructorArgs([$this->fixtureApiKey])
                           ->getMock();
        $apiUrlMock->expects($this->once())->method('getListUrl')
                   ->with(
                       $this->equalTo($lat),
                       $this->equalTo($lng),
                       $this->equalTo($radius),
                       $this->equalTo($sort),
                       $this->equalTo($ftype)
                   )
                   ->willReturn('requestUrl');

        $this->api = $this->getApiClientRequestMock( $apiUrlMock, $cantRequest, $cantDecode, $responseContent );

        if ($cantRequest || $cantDecode) {
            $this->expectException(ApiException::class);
        }

        $return = $this->callMethod(
            $this->api,
            'search',
            [$lat, $lng, $ftype, $radius, $sort]
        );

        if ($expected) {
            $this->assertSame(
                $return,
                $expected
            );
        }
    }

    /**
     * @return array
     */
    public function searchDataProvider(): array
    {
        return array_merge(
            $this->getExceptionDataProvider(),
            [
                'apiReturnsSinglePrice' => [
                    $this->getApiDataProvider()->getSinglePriceResponse(),
                    ApiClient::TYPE_E10,
                    false,
                    false,
                    $this->getApiDataProvider()->getExpectedSinglePriceResponse()
                ],
                'apiReturnsAllPrices' => [
                    $this->getApiDataProvider()->getAllPricesResponse(),
                    ApiClient::TYPE_ALL,
                    false,
                    false,
                    $this->getApiDataProvider()->getExpectedAllPricesResponse()
                ]
            ]
        );
    }

    /**
     * @test
     * @covers ApiClient::detail()
     * @dataProvider detailDataProvider
     *
     * @return void
     * @throws ReflectionException
     */
    public function testDetail($responseContent, $fuelType, $cantRequest, $cantDecode)
    {
        unset($fuelType);
        $stationId = 'stationId';

        /** @var MockObject|ApiUrl $apiUrlMock */
        $apiUrlMock = $this->getMockBuilder(ApiUrl::class)
                           ->onlyMethods(['getStationDetailUrl'])
                           ->setConstructorArgs([$this->fixtureApiKey])
                           ->getMock();
        $apiUrlMock->expects($this->any())->method('getStationDetailUrl')
                   ->with(
                       $this->equalTo($stationId)
                   )
                   ->willReturn('requestUrl');

        $this->api = $this->getApiClientRequestMock( $apiUrlMock, $cantRequest, $cantDecode, $responseContent );

        if ($cantRequest || $cantDecode) {
            $this->expectException(ApiException::class);
        }

        $return = $this->callMethod(
            $this->api,
            'detail',
            [$stationId]
        );

        if (!$cantRequest && !$cantDecode) {
            $this->assertInstanceOf(
                PetrolStation::class,
                $return
            );
        }
    }

    /**
     * @return array
     */
    public function detailDataProvider(): array
    {
        return array_merge(
            $this->getExceptionDataProvider(),
            [
                'apiReturnsValid' => [
                    $this->getApiDataProvider()->getStationDetailResponse(),
                    ApiClient::TYPE_E10,
                    false,
                    false,
                    ''
                ]
            ]
        );
    }

    /**
     * @test
     * @covers ApiClient::prices()
     * @dataProvider pricesDataProvider
     *
     * @return void
     * @throws ReflectionException
     */
    public function testPrices($responseContent, $fuelType, $cantRequest, $cantDecode)
    {
        unset($fuelType);
        $stationList = ['stationId1','stationId2'];

        /** @var MockObject|ApiUrl $apiUrlMock */
        $apiUrlMock = $this->getMockBuilder(ApiUrl::class)
                           ->onlyMethods(['getPricesUrl'])
                           ->setConstructorArgs([$this->fixtureApiKey])
                           ->getMock();
        $apiUrlMock->expects($this->any())->method('getPricesUrl')
                   ->with(
                       $this->equalTo($stationList)
                   )
                   ->willReturn('requestUrl');

        $this->api = $this->getApiClientRequestMock( $apiUrlMock, $cantRequest, $cantDecode, $responseContent );

        if ($cantRequest || $cantDecode) {
            $this->expectException(ApiException::class);
        }

        $return = $this->callMethod(
            $this->api,
            'prices',
            [$stationList]
        );

        if (!$cantRequest && !$cantDecode) {
            $this->assertInstanceOf(
                PriceInfo::class,
                $return['stationId1']
            );
            $this->assertInstanceOf(
                PriceInfo::class,
                $return['stationId2']
            );
        }
    }

    /**
     * @return array
     */
    public function pricesDataProvider(): array
    {
        return array_merge(
            $this->getExceptionDataProvider(),
            [
                'apiReturnsValid' => [
                    'responseContent'   => $this->getApiDataProvider()->getPricesResponse(),
                    'fuelType'          => ApiClient::TYPE_E10,
                    'cantRequest'       => false,
                    'cantDecode'        => false,
                    'expected'          => ''
                ],
                'apiReturnsLimited' => [
                    $this->getApiDataProvider()->getPricesLimitedResponse(),
                    ApiClient::TYPE_E10,
                    false,
                    false,
                    ''
                ],
                'apiReturnsStationClosed' => [
                    $this->getApiDataProvider()->getPricesStationClosedResponse(),
                    ApiClient::TYPE_E10,
                    false,
                    false,
                    ''
                ],
                'apiReturnsNoPrices' => [
                    $this->getApiDataProvider()->getPricesNoPricesResponse(),
                    ApiClient::TYPE_E10,
                    false,
                    false,
                    ''
                ],
                'apiReturnsNoStations' => [
                    $this->getApiDataProvider()->getPricesNoStationsResponse(),
                    ApiClient::TYPE_E10,
                    true,
                    false,
                    ''
                ]
            ]
        );
    }

    /**
     * @test
     * @covers ApiClient::complaint()
     * @dataProvider complaintDataProvider
     *
     * @return void
     * @throws ReflectionException
     */
    public function testComplaint($correctionMissing, $cantRequest, $cantDecode)
    {
        $stationId = 'stationId1';
        $type = 'complaintType';
        $correction = 'correctionValue';

        /** @var MockObject|ApiClient $apiClientMock */
        $apiClientMock = $this->getMockBuilder( ApiClient::class )
            ->onlyMethods( [
                'checkForMissingCorrection',
                'request',
                'decodeResponse'
            ] )
            ->setConstructorArgs( [ $this->fixtureApiKey ] )
            ->getMock();

        if ($correctionMissing) {
            $apiClientMock->expects( $this->once() )->method( 'checkForMissingCorrection' )
                                                    ->willThrowException( new ApiException() );
        } else {
            $apiClientMock->expects( $this->once() )->method( 'checkForMissingCorrection' );
        }

        $invokation = $apiClientMock->expects($correctionMissing ? $this->never() : $this->once())
                                    ->method('request');
        $cantRequest ? $invokation->willThrowException(new ApiException()) : $invokation->willReturn($this->jsonFixture);

        $invokation = $apiClientMock->expects( $cantRequest || $correctionMissing ? $this->never() : $this->once() )
                      ->method( 'decodeResponse' )->with( $this->equalTo( $this->jsonFixture ) );
        $cantDecode ? $invokation->willThrowException( new ApiException() ) : $invokation->willReturn( 'responseContent' );

        if ( $cantDecode ) {
            $apiClientMock->expects( $cantRequest || $correctionMissing ? $this->never() : $this->once() )
                          ->method( 'decodeResponse' )->with( $this->equalTo( $this->jsonFixture ) )
                          ->willThrowException( new ApiException() );
        } else {
            $apiClientMock->expects( $cantRequest || $correctionMissing ? $this->never() : $this->once() )
                          ->method( 'decodeResponse' )->with( $this->equalTo( $this->jsonFixture ) )
                          ->willReturn( 'responseContent' );
        }

        $this->api = $apiClientMock;

        if ($correctionMissing || $cantRequest || $cantDecode) {
            $this->expectException(ApiException::class);
        }

        $this->callMethod(
            $this->api,
            'complaint',
            [$stationId, $type, $correction]
        );
    }

    /**
     * @return array
     */
    public function complaintDataProvider(): array
    {
        return [
            'missingCorrectionValue' => [
                'correctionMissing' => true,
                'cantRequest'       => true,
                'cantDecode'        => true
            ],
            'apiRequestError' => [
                'correctionMissing' => false,
                'cantRequest'       => true,
                'cantDecode'        => true
            ],
            'apiResponseError' => [
                'correctionMissing' => false,
                'cantRequest'       => false,
                'cantDecode'        => true
            ],
            'apiRequestOk' => [
                'correctionMissing' => false,
                'cantRequest'       => false,
                'cantDecode'        => false
            ]
        ];
    }

    /**
     * @test
     * @covers ApiClient::request()
     *
     * @dataProvider requestDataProvider
     *
     * @param $statusCode
     * @param $expectException
     *
     * @throws ReflectionException
     */
    public function testRequest($statusCode, $expectException)
    {
        $method = 'fixtureMethod';
        $url = 'fixtureUrl';
        $postArgs = ['postArgs'];
        $expected = 'bodyContent';
        $responseBody = new BufferStream(1);
        $responseBody->write($expected);

        /** @var MockObject|Response $responseMock */
        $responseMock = $this->getMockBuilder(Response::class)
            ->onlyMethods([
                'getStatusCode',
                'getBody'
            ])
            ->getMock();
        $responseMock->expects($this->atLeastOnce())->method('getStatusCode')->willReturn($statusCode);
        $responseMock->method('getBody')->willReturn($responseBody);

        /** @var MockObject|Client $requestClientMock */
        $requestClientMock = $this->getMockBuilder(Client::class)
                           ->onlyMethods(['request'])
                           ->getMock();
        $requestClientMock->expects($this->once())->method('request')->with(
            $this->equalTo($method),
            $this->equalTo($url)
        )->willReturn($responseMock);

        $this->api->requestClient = $requestClientMock;

        if ($expectException) {
            $this->expectException(ApiException::class);
        }

        $return = $this->callMethod(
            $this->api,
            'request',
            [$url, $method, $postArgs]
        );

        if (false === $expectException) {
            $this->assertSame(
                $return,
                $expected
            );
        }
    }

    /**
     * @return array[]
     */
    public function requestDataProvider(): array
    {
        return [
            'no status 200' => [500, true],
            'status 200' => [200, false]
        ];
    }

    /**
     * @test
     * @dataProvider decodeResponseDataProvider
     * @covers       ApiClient::decodeResponse()
     *
     * @param $json
     * @param $expectException
     * @param $expected
     *
     * @throws ReflectionException
     */
    public function testDecodeResponse($json, $expectException, $expected)
    {
        if ($expectException) {
            $this->expectException(ApiException::class);
        }

        $data = $this->callMethod(
            $this->api,
            'decodeResponse',
            [$json, is_array($expected)]
        );

        if (false === $expectException) {
            $this->assertEquals(
                $data,
                $expected
            );
        }
    }

    /**
     * @return array[]
     */
    public function decodeResponseDataProvider(): array
    {
        return [
            'empty json'    => ['', true, false],
            'NOK array'    => [json_encode($this->getApiDataProvider()->getNotOkArrayResponse()), true, []],
            'NOK object'    => [json_encode($this->getApiDataProvider()->getNotOkObjectResponse()), true, new stdClass()],
            'OK array'    => [json_encode($this->getApiDataProvider()->getStationDetailResponse()), false, $this->getApiDataProvider()->getStationDetailResponse()],
            'OK object'    => [json_encode($this->getApiDataProvider()->getAllPricesResponse()), false, $this->getApiDataProvider()->getAllPricesResponse()],
        ];
    }

    /**
     * @test
     * @dataProvider checkForMissingCorrectionDataProvider
     * @covers ApiClient::checkForMissingCorrection()
     *
     * @param $correctionRequired
     * @param $correction
     * @param $expectException
     *
     * @throws ReflectionException
     */
    public function testCheckForMissingCorrection($correctionRequired, $correction, $expectException)
    {
        $type = 'complaintType';

        /** @var Complaint|MockObject $complaintMock */
        $complaintMock = $this->getMockBuilder(Complaint::class)
            ->onlyMethods(['isCorrectionRequired'])
            ->getMock();
        $complaintMock->expects($this->once())->method('isCorrectionRequired')->willReturn($correctionRequired);

        $this->api->complaint = $complaintMock;

        if ($expectException) {
            $this->expectException(ApiException::class);
        }

        $this->callMethod(
            $this->api,
            'checkForMissingCorrection',
            [$type, $correction]
        );
    }

    /**
     * @return array[]
     */
    public function checkForMissingCorrectionDataProvider(): array
    {
        return [
            'missing correction' => [true, null, true],
            'has correction' => [true, 'correction', false],
            'no correction required' => [false, null, false],
        ];
    }

    /**
     * @return array[]
     */
    public function getExceptionDataProvider(): array
    {
        return [
            'apiCantRequest' => [
                'responseContent'   => $this->getApiDataProvider()->getNotOkArrayResponse(),
                'fuelType'          => ApiClient::TYPE_E10,
                'cantRequest'       => true,
                'cantDecode'        => true,
                'expected'          => ''
            ],
            'apiCantDecode' => [
                'responseContent'   => $this->getApiDataProvider()->getNotOkArrayResponse(),
                'fuelType'          => ApiClient::TYPE_E10,
                'cantRequest'       => false,
                'cantDecode'        => true,
                'expected'          => ''
            ],
        ];
    }

    /**
     * @return ApiClientDataProvider
     */
    public function getApiDataProvider(): ApiClientDataProvider
    {
        return new ApiClientDataProvider($this);
    }

    /**
     * @param ApiUrl|MockObject $apiUrlMock
     * @param                   $cantRequest
     * @param                   $cantDecode
     * @param                   $responseContent
     *
     * @return ApiClient|MockObject
     */
    protected function getApiClientRequestMock(
        MockObject $apiUrlMock,
        $cantRequest,
        $cantDecode,
        $responseContent
    )
    {
        /** @var MockObject|ApiClient $apiClientMock */
        $apiClientMock = $this->getMockBuilder( ApiClient::class )
            ->onlyMethods( [
                'request',
                'decodeResponse'
            ] )
            ->setConstructorArgs( [ $this->fixtureApiKey ] )
            ->getMock();

        $apiClientMock->apiUrl = $apiUrlMock;

        if ( $cantRequest ) {
            $apiClientMock->expects( $this->once() )->method( 'request' )->willThrowException( new ApiException() );
        } else {
            $apiClientMock->expects( $this->once() )->method( 'request' )->willReturn( $this->jsonFixture );
        }

        if ( $cantDecode ) {
            $apiClientMock->expects( $cantRequest ? $this->never() : $this->once() )
                ->method( 'decodeResponse' )->with( $this->equalTo( $this->jsonFixture ) )
                ->willThrowException( new ApiException() );
        } else {
            $apiClientMock->expects( $cantRequest ? $this->never() : $this->once() )
                ->method( 'decodeResponse' )->with( $this->equalTo( $this->jsonFixture ) )
                ->willReturn( $responseContent );
        }

        return $apiClientMock;
    }
}
