<?php

/**
 * This Software is the property of Data Development and is protected
 * by copyright law - it is NOT Freeware.
 * Any unauthorized use of this software without a valid license
 * is a violation of the license agreement and will be prosecuted by
 * civil and criminal law.
 * http://www.shopmodule.com
 *
 * @copyright (C) D3 Data Development (Inh. Thomas Dartsch)
 * @author        D3 Data Development - Daniel Seifert <support@shopmodule.com>
 * @link          http://www.oxidmodule.com
 */

namespace DanielS\Tankerkoenig\Tests;

use DanielS\Tankerkoenig\Complaint;
use ReflectionException;

class ComplaintTest extends ApiTestCase
{
    public Complaint $complaint;

    public function setUp(): void
    {
        parent::setUp();

        $this->complaint = new Complaint();
    }

    /**
     * @test
     * @covers Complaint::isCorrectionRequired
     * @dataProvider isCorrectionRequiredDataProvider
     *
     * @param $type
     * @param $expected
     *
     * @throws ReflectionException
     */
    public function testIsCorrectionRequired($type, $expected)
    {
        $this->assertSame(
            $expected,
            $this->callMethod(
                $this->complaint,
                'isCorrectionRequired',
                [$type]
            )
        );
    }

    /**
     * @return array[]
     */
    public function isCorrectionRequiredDataProvider(): array
    {
        return [
            'required'      => [Complaint::WRONG_PRICE_E10, true],
            'not required'  => [Complaint::WRONG_STATUS_CLOSED, false],
            'unknown'       => ['unknown', false],
        ];
    }
}
