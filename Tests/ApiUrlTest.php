<?php

/**
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) 2017 Tobias Lang
 * @copyright Copyright (c) 2022-present Daniel Seifert <git@daniel-seifert.com>
 */

declare(strict_types=1);

namespace DanielS\Tankerkoenig\Tests;

use DanielS\Tankerkoenig\ApiException;
use DanielS\Tankerkoenig\ApiUrl;
use ReflectionException;

class ApiUrlTest extends ApiTestCase
{
    public string $fixtureApiKey = 'fixtureApiKey';
    public ApiUrl $apiUrl;

    public function setUp(): void
    {
        parent::setUp();

        $this->apiUrl = new ApiUrl($this->fixtureApiKey);
    }

    /**
     * @test
     * @covers ApiUrl::getBaseUri
     * @throws ReflectionException
     * @return void
     */
    public function testGetBaseUri(): void
    {
        $baseUri = 'baseUriFixture';
        $this->apiUrl->baseUri = $baseUri;

        $this->assertSame(
            $baseUri,
            $this->callMethod(
                $this->apiUrl,
                'getBaseUri'
            )
        );
    }

    /**
     * @test
     * @covers ApiUrl::getListUrl
     * @throws ReflectionException
     * @return void
     */
    public function testGetListUrl(): void
    {
        $lat = 52.521;
        $lng = 13.413;
        $radius = 234;
        $sort = 'sortFixture';
        $type = 'typeFixture';

        $url = $this->callMethod(
            $this->apiUrl,
            'getListUrl',
            [$lat, $lng, $radius, $sort, $type]
        );

        $this->assertTrue(
            strpos($url, '.php') > 0 &&
            strpos($url, (string) $lat) > 0 &&
            strpos($url, (string) $lng) > 0 &&
            strpos($url, (string) $radius) > 0 &&
            strpos($url, $sort) > 0 &&
            strpos($url, $type) > 0 &&
            strpos($url, $this->fixtureApiKey) > 0
        );
    }

    /**
     * @test
     * @covers ApiUrl::getStationDetailUrl
     * @throws ReflectionException
     * @return void
     */
    public function testGetStationDetailUrl(): void
    {
        $stationId = 'stationIdFixture';

        $url = $this->callMethod(
            $this->apiUrl,
            'getStationDetailUrl',
            [$stationId]
        );

        $this->assertTrue(
            strpos($url, '.php') > 0 &&
            strpos($url, $stationId) > 0 &&
            strpos($url, $this->fixtureApiKey) > 0
        );
    }

    /**
     * @test
     * @dataProvider getPricesUrlDataProvider
     * @covers       ApiUrl::getPricesUrl
     * @param array<string> $stationList
     * @param bool $expectException
     * @return void
     * @throws ReflectionException
     */
    public function testGetPricesUrl(array $stationList, bool $expectException)
    {
        if ($expectException) {
            $this->expectException(ApiException::class);
        }

        $url = $this->callMethod(
            $this->apiUrl,
            'getPricesUrl',
            [$stationList]
        );

        if (false === $expectException) {
            $this->assertTrue(
                strpos($url, '.php') > 0 &&
                strpos($url, (string) reset($stationList)) > 0 &&
                strpos($url, (string) end($stationList)) > 0 &&
                strpos($url, $this->fixtureApiKey) > 0
            );
        }
    }

    /**
     * @return array<string, array<array<int>|bool>>
     */
    public function getPricesUrlDataProvider(): array
    {
        return [
            'stationlist to small'  => [[], true],
            'stationlist to large'  => [range(572, 572+15), true],
            'stationlist ok'  => [range(572, 572+8), false],
        ];
    }

    /**
     * @test
     * @covers ApiUrl::getComplaintUrl
     * @throws ReflectionException
     * @return void
     */
    public function testGetComplaintUrl(): void
    {
        $url = $this->callMethod(
            $this->apiUrl,
            'getComplaintUrl'
        );

        $this->assertTrue(
            strpos($url, '.php') > 0 &&
            strpos($url, $this->fixtureApiKey) > 0
        );
    }
}
