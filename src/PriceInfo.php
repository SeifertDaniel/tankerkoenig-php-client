<?php

/**
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) 2017 Tobias Lang
 * @copyright Copyright (c) 2022-present Daniel Seifert <git@daniel-seifert.com>
 */

declare(strict_types=1);

namespace DanielS\Tankerkoenig;

/** @phpstan-consistent-constructor */
class PriceInfo
{
    public string $stationId;
    public string $status;
    public float|null $e5;
    public float|null $e10;
    public float|null $diesel;

    /**
     * @param string[] $array
     * @return static
     */
    public static function fromApiArray(array $array): self
    {
        return new static(
            $array['stationId'],
            $array['status'],
            $array[ApiClient::TYPE_E5] ?? null,
            $array[ApiClient::TYPE_E10] ?? null,
            $array[ApiClient::TYPE_DIESEL] ?? null
        );
    }

    /**
     * @param string     $stationId
     * @param string     $status
     * @param float|null $e5
     * @param float|null $e10
     * @param float|null $diesel
     */
    public function __construct(
        string $stationId,
        string $status,
        float $e5 = null,
        float $e10 = null,
        float $diesel = null
    ) {
        $this->stationId = $stationId;
        $this->status = $status;
        $this->e5     = $e5;
        $this->e10 = $e10;
        $this->diesel = $diesel;
    }
}
