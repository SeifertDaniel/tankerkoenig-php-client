<?php

namespace DanielS\Tankerkoenig\Tests;

use DanielS\Tankerkoenig\PriceInfo;

class PriceInfoTest extends ApiTestCase
{
    /**
     * @return void
     */
    public function testConstruct(): void
    {
        $stationId = 'stationIdFixture';
        $status = 'statusFixture';
        $e5 = 1.90;
        $e10 = 1.85;
        $diesel = 1.80;

        $priceinfo = new PriceInfo($stationId, $status, $e5, $e10, $diesel);

        $this->assertSame($priceinfo->stationId, $stationId);
        $this->assertSame($priceinfo->status, $status);
        $this->assertSame($priceinfo->e5, $e5);
        $this->assertSame($priceinfo->e10, $e10);
        $this->assertSame($priceinfo->diesel, $diesel);
    }

    /**
     * @return void
     */
    public function testConstructNull(): void
    {
        $stationId = 'stationIdFixture';
        $status = 'statusFixture';
        $e5 = null;
        $e10 = null;
        $diesel = null;

        $priceinfo = new PriceInfo($stationId, $status, $e5, $e10, $diesel);

        $this->assertSame($priceinfo->stationId, $stationId);
        $this->assertSame($priceinfo->status, $status);
        $this->assertSame($priceinfo->e5, $e5);
        $this->assertSame($priceinfo->e10, $e10);
        $this->assertSame($priceinfo->diesel, $diesel);
    }
}
