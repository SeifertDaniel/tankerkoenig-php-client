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
     * @covers       Complaint::isCorrectionRequired
     * @dataProvider isCorrectionRequiredDataProvider
     *
     * @param string $type
     * @param bool $expected
     *
     * @throws ReflectionException
     *
     * @return void
     */
    public function testIsCorrectionRequired(string $type, bool $expected): void
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
     * @return array<string, array<string, bool>>
     */
    public function isCorrectionRequiredDataProvider(): array
    {
        //@phpstan-ignore-next-line
        return [
            'required'      => [Complaint::WRONG_PRICE_E10, true],
            'not required'  => [Complaint::WRONG_STATUS_CLOSED, false],
            'unknown'       => ['unknown', false],
        ];
    }
}
