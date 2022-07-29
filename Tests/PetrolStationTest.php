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

use DanielS\Tankerkoenig\PetrolStation;

class PetrolStationTest extends ApiTestCase
{
    /**
     * @test
     * @covers PetrolStation::_construct
     *
     * @return void
     */
    public function testConstruct(): void
    {
        $id = 'idFixture';
        $name = 'nameFixture';
        $brand = 'brandFixture';
        $street = 'streetFixture';
        $houseno = 'housenoFixture';
        $postCode = 'postcodeFixture';
        $place = 'placeFixture';
        $openingtimes = ['openingTimesFixture'];
        $overrides = ['overridesFixture'];
        $wholeday = true;
        $isopen = true;
        $e5 = 1.90;
        $e10 = 1.85;
        $diesel = 1.80;
        $lat = 52.521;
        $lng = 13.413;
        $state = 'stateFixture';

        $station = new PetrolStation(
            $id,
            $name,
            $brand,
            $street,
            $houseno,
            $postCode,
            $place,
            $openingtimes,
            $overrides,
            $wholeday,
            $isopen,
            $e5,
            $e10,
            $diesel,
            $lat,
            $lng,
            $state
        );

        $this->assertSame($station->id, $id);
        $this->assertSame($station->name, $name);
        $this->assertSame($station->brand, $brand);
        $this->assertSame($station->street, $street);
        $this->assertSame($station->houseNumber, $houseno);
        $this->assertSame($station->postCode, $postCode);
        $this->assertSame($station->place, $place);
        $this->assertSame($station->openingTimes, $openingtimes);
        $this->assertSame($station->overrides, $overrides);
        $this->assertSame($station->wholeDay, $wholeday);
        $this->assertSame($station->isOpen, $isopen);
        $this->assertSame($station->e5, $e5);
        $this->assertSame($station->e10, $e10);
        $this->assertSame($station->diesel, $diesel);
        $this->assertSame($station->lat, $lat);
        $this->assertSame($station->lng, $lng);
        $this->assertSame($station->state, $state);
    }
}
