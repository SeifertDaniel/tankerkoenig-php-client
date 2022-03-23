<?php

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
     * @param array $arguments
     *
     * @return mixed
     * @throws ReflectionException
     */
    public function callMethod($object, $methodName, array $arguments = array())
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
     * @throws ReflectionException
     */
    public function setValue($object, $valueName, $value)
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
     * @param mixed $value
     * @return mixed
     * @throws ReflectionException
     */
    public function getValue($object, $valueName)
    {
        $reflection = new ReflectionClass($object);
        $property = $reflection->getProperty($valueName);
        $property->setAccessible(true);
        return $property->getValue($object);
    }
}