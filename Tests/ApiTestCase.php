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

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;

abstract class ApiTestCase extends TestCase
{
    /**
     * Calls a private or protected object method.
     *
     * @param object $object
     * @param string $methodName
     * @param array<int, array<string>|float|int|bool|string> $arguments
     *
     * @return mixed
     * @throws ReflectionException
     */
    public function callMethod(object $object, string $methodName, array $arguments = []): mixed
    {
        $class = new ReflectionClass($object);
        $method = $class->getMethod($methodName);
        $method->setAccessible(true);
        return $method->invokeArgs($object, $arguments);
    }

    /**
     * Sets a private or protected property in defined class instance
     *
     * @param object $object
     * @param string $valueName
     * @param mixed $value
     * @return void
     * @throws ReflectionException
     */
    public function setValue(object $object, string $valueName, mixed $value): void
    {
        $reflection = new ReflectionClass($object);
        $property = $reflection->getProperty($valueName);
        $property->setAccessible(true);
        $property->setValue($object, $value);
    }

    /**
     * get a private or protected property from defined class instance
     *
     * @param object $object
     * @param string $valueName
     * @return mixed
     * @throws ReflectionException
     */
    public function getValue(object $object, string $valueName): mixed
    {
        $reflection = new ReflectionClass($object);
        $property = $reflection->getProperty($valueName);
        $property->setAccessible(true);
        return $property->getValue($object);
    }
}
