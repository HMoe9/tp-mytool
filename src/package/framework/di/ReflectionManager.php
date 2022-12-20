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
     * @param string $className
     *
     * @return ReflectionClass
     * @throws ReflectionException
     * @throws RuntimeException
     */
    public static function reflectClass(string $className): ReflectionClass
    {
        if (!isset(static::$container['class'][$className])) {
            if (!class_exists($className) && !interface_exists($className) && !trait_exists($className)) {
                throw new RuntimeException(sprintf('Class %s not exist', $className));
            }
            static::$container['class'][$className] = new ReflectionClass($className);
        }
        return static::$container['class'][$className];
    }

    /**
     * @param string $className
     * @param bool $newInstance
     *
     * @return mixed|object
     * @throws ReflectionException
     * @throws RuntimeException
     */
    public static function getInstance(string $className, bool $newInstance = false)
    {
        if ($newInstance) {
            return static::reflectClass($className)->newInstance();
        }

        if (!isset(static::$container['instance'][$className])) {
            static::$container['instance'][$className] = static::reflectClass($className)->newInstance();
        }
        return static::$container['instance'][$className];
    }

    /**
     * @param string $className
     * @param string $method
     *
     * @return ReflectionMethod
     * @throws ReflectionException
     * @throws RuntimeException
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
     * @param string $className
     * @param string $property
     *
     * @return ReflectionProperty
     * @throws ReflectionException
     * @throws RuntimeException
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
