<?php
declare(strict_types=1);

namespace tp\mytool\package\framework\di;

use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionProperty;
use tp\mytool\package\framework\exception\RuntimeException;

class ReflectionManager extends MetadataCollector
{
    /**
     * @var array
     */
    protected static $container = [];

    /**
     * @throws RuntimeException
     * @throws ReflectionException
     */
    public static function reflectClass(string $className): ReflectionClass
    {
        if (!isset(static::$container['class'][$className])) {
            if (!class_exists($className) && !interface_exists($className) && !trait_exists($className)) {
                throw new RuntimeException("Class {$className} not exist");
            }
            static::$container['class'][$className] = new ReflectionClass($className);
        }
        return static::$container['class'][$className];
    }

    /**
     * @throws RuntimeException
     * @throws ReflectionException
     */
    public static function getInstance(string $className)
    {
        if (!isset(static::$container['instance'][$className])) {
            static::$container['instance'][$className] = static::reflectClass($className)->newInstance();
        }
        return static::$container['instance'][$className];
    }

    /**
     * @throws RuntimeException
     * @throws ReflectionException
     */
    public static function reflectMethod(string $className, string $method): ReflectionMethod
    {
        $key = $className . '::' . $method;
        if (!isset(static::$container['method'][$key])) {
            static::$container['method'][$key] = static::reflectClass($className)->getMethod($method);
        }
        return static::$container['method'][$key];
    }

    /**
     * @throws RuntimeException
     * @throws ReflectionException
     */
    public static function reflectProperty(string $className, string $property): ReflectionProperty
    {
        $key = $className . '::' . $property;
        if (!isset(static::$container['property'][$key])) {
            static::$container['property'][$key] = static::reflectClass($className)->getProperty($property);
        }
        return static::$container['property'][$key];
    }

    public static function clear(?string $key = null): void
    {
        if ($key === null) {
            static::$container = [];
        }
    }

    public static function getContainer(): array
    {
        return self::$container;
    }
}
