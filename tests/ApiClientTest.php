<?php

namespace DanielS\Tankerkoenig\Tests;

use DanielS\Tankerkoenig\ApiClient;
use DanielS\Tankerkoenig\ApiException;
use DanielS\Tankerkoenig\ApiUrl;
use DanielS\Tankerkoenig\GasStation;
use DanielS\Tankerkoenig\PriceInfo;
use DanielS\Tankerkoenig\Tests\DataProviders\ApiClient as ApiClientDataProvider;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamFile;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use stdClass;

class ApiClientTest extends ApiTestCase
{
    public $fixtureApiKey = 'fixtureApiKey';
    /** @var ApiClient */
    public $api;

    public function setUp():void
    {
        $this->api = new ApiClient($this->fixtureApiKey);
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testConstruct()
    {
        $this->assertSame(
            $this->fixtureApiKey,
            $this->getValue(
                $this->api,
                'apiKey'
            )
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
    public function testSearch($responseContent, $fuelType, $throwsException, $expected = null)
    {
        $lat = 52.521231478503495;
        $lng = 13.413301092880559;
        $ftype = $fuelType;
        $radius = 10;
        $sort = ApiClient::SORT_PRICE;

        $requestUrlMock = $this->getRequestUrlMock($responseContent);

        /** @var MockObject|ApiUrl $apiUrlMock */
        $apiUrlMock = $this->getMockBuilder(ApiUrl::class)
            ->onlyMethods(['getListUrl'])
            ->setConstructorArgs([$this->fixtureApiKey])
            ->getMock();
        $apiUrlMock->expects($this->any())->method('getListUrl')
            ->with(
                $this->equalTo($lat),
                $this->equalTo($lng),
                $this->equalTo($radius),
                $this->equalTo($sort),
                $this->equalTo($ftype)
            )
            ->willReturn($requestUrlMock->url());

        /** @var MockObject|ApiClient $apiClientMock */
        $apiClientMock = $this->getMockBuilder(ApiClient::class)
            ->onlyMethods(['getApiUrlInstance'])
            ->setConstructorArgs([$this->fixtureApiKey])
            ->getMock();
        $apiClientMock->expects($this->any())->method('getApiUrlInstance')->willReturn($apiUrlMock);

        $this->api = $apiClientMock;

        if ($throwsException) {
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

    public function searchDataProvider()
    {
        return array_merge(
            $this->getExceptionDataProvider(),
            [
                'apiReturnsSinglePrice' => [
                    $this->getApiDataProvider()->getSinglePriceJson(),
                    ApiClient::TYPE_E10,
                    false,
                    $this->getApiDataProvider()->getExpectedSinglePriceResponse()
                ],
                'apiReturnsAllPrices' => [
                    $this->getApiDataProvider()->getAllPricesJson(),
                    ApiClient::TYPE_ALL,
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
    public function testDetail($responseContent, $fuelType, $throwsException)
    {
        $stationId = 'stationId';

        $requestUrlMock = $this->getRequestUrlMock($responseContent);

        /** @var MockObject|ApiUrl $apiUrlMock */
        $apiUrlMock = $this->getMockBuilder(ApiUrl::class)
            ->onlyMethods(['getStationDetailUrl'])
            ->setConstructorArgs([$this->fixtureApiKey])
            ->getMock();
        $apiUrlMock->expects($this->any())->method('getStationDetailUrl')
            ->with(
                $this->equalTo($stationId)
            )
            ->willReturn($requestUrlMock->url());

        /** @var MockObject|ApiClient $apiClientMock */
        $apiClientMock = $this->getMockBuilder(ApiClient::class)
            ->onlyMethods(['getApiUrlInstance'])
            ->setConstructorArgs([$this->fixtureApiKey])
            ->getMock();
        $apiClientMock->expects($this->any())->method('getApiUrlInstance')->willReturn($apiUrlMock);

        $this->api = $apiClientMock;

        if ($throwsException) {
            $this->expectException(ApiException::class);
        }

        $return = $this->callMethod(
            $this->api,
            'detail',
            [$stationId]
        );

        if (false === $throwsException) {
            $this->assertInstanceOf(
                GasStation::class,
                $return
            );
        }
    }

    public function detailDataProvider()
    {
        return array_merge(
            $this->getExceptionDataProvider(),
            [
                'apiReturnsValid' => [
                    $this->getApiDataProvider()->getStationDetailJson(),
                    ApiClient::TYPE_E10,
                    false
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
    public function testPrices($responseContent, $fuelType, $throwsException)
    {
        $stationList = ['stationId1','stationId2'];

        $requestUrlMock = $this->getRequestUrlMock($responseContent);

        /** @var MockObject|ApiUrl $apiUrlMock */
        $apiUrlMock = $this->getMockBuilder(ApiUrl::class)
            ->onlyMethods(['getPricesUrl'])
            ->setConstructorArgs([$this->fixtureApiKey])
            ->getMock();
        $apiUrlMock->expects($this->any())->method('getPricesUrl')
            ->with(
                $this->equalTo($stationList)
            )
            ->willReturn($requestUrlMock->url());

        /** @var MockObject|ApiClient $apiClientMock */
        $apiClientMock = $this->getMockBuilder(ApiClient::class)
            ->onlyMethods(['getApiUrlInstance'])
            ->setConstructorArgs([$this->fixtureApiKey])
            ->getMock();
        $apiClientMock->expects($this->any())->method('getApiUrlInstance')->willReturn($apiUrlMock);

        $this->api = $apiClientMock;

        if ($throwsException) {
            $this->expectException(ApiException::class);
        }

        $return = $this->callMethod(
            $this->api,
            'prices',
            [$stationList]
        );

        if (false === $throwsException) {
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

    public function pricesDataProvider()
    {
        return array_merge(
            $this->getExceptionDataProvider(),
            [
                'apiReturnsValid' => [
                    $this->getApiDataProvider()->getPricesJson(),
                    ApiClient::TYPE_E10,
                    false
                ]
            ]
        );
    }

    public function getExceptionDataProvider()
    {
        return [
            'apiReturnsNothing' => ['', ApiClient::TYPE_E10, true],
            'apiReturnsNotOk' => [$this->getApiDataProvider()->getNotOkJson(), ApiClient::TYPE_E10, true],
        ];
    }

    /**
     * @return ApiClientDataProvider
     */
    public function getApiDataProvider()
    {
        return new ApiClientDataProvider($this);
    }

    /**
     * @param $content
     * @return vfsStreamFile
     */
    protected function getRequestUrlMock($content): vfsStreamFile
    {
        $structure = ['test_directory' => ['test.file' => 'testContent']];
        vfsStream::setup();
        $fsRoot = vfsStream::create($structure);
        $fsTestDirectory = $fsRoot->getChild('test_directory');
        return vfsStream::newFile('filename.ext')->at($fsTestDirectory)->setContent($content);
    }
}
