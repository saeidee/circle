<?php

namespace Tests;

use ReflectionClass;
use ReflectionException;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Call protected/private method of a class.
     * @param object &$object Instantiated object that we will run method on.
     * @param string $methodName Method name to all call.
     * @param array $parameters Array of parameters to be pass into method.
     * @return mixed Method return.
     * @throws ReflectionException
     */
    protected function invokeMethod(&$object, string $methodName, array $parameters = [])
    {
        $reflection = new ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    /**
     * @param mixed $object
     * @param string $property
     * @param mixed $value
     * @throws ReflectionException
     */
    public function assertProperty($object, string $property, $value)
    {
        $this->assertEquals($value, $this->getPrivateProperty($object, $property));
    }

    /**
     * For getting private or protected property of an object
     * @param mixed $object
     * @param string $property
     * @return mixed
     * @throws ReflectionException
     */
    public function getPrivateProperty($object, string $property)
    {
        $reflection = new ReflectionClass($object);
        $reflectionProperty = $reflection->getProperty($property);
        $reflectionProperty->setAccessible(true);

        return $reflectionProperty->getValue($object);
    }
}
