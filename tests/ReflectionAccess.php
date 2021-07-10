<?php

namespace App\Tests;

/**
 * Class ReflectionAccess
 * @package App\Tests
 */
abstract class ReflectionAccess
{
    /**
     * @param object $instance
     * @param string $property
     * @return mixed
     * @throws \ReflectionException
     */
    public static function getValue(object $instance, string $property)
    {
        $reflector = new \ReflectionClass($instance);
        $reflector_property = $reflector->getProperty($property);
        $reflector_property->setAccessible(true);

        return $reflector_property->getValue($instance);
    }

    /**
     * @param object $instance
     * @return array
     */
    public static function getConstants(object $instance): array
    {
        $reflector = new \ReflectionClass($instance);
        return $reflector->getConstants();
    }
}
