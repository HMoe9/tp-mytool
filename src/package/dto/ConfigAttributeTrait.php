<?php
declare(strict_types=1);

namespace tp\mytool\package\dto;

use tp\mytool\package\framework\di\ReflectionManager;
use tp\mytool\package\framework\exception\RuntimeException;
use tp\mytool\package\util\Helper;
use ReflectionException;

/**
 * 配置参数、固定值参数
 *
 * Trait ConfigAttributeTrait
 * @package tp\mytool\package\dto
 */
trait ConfigAttributeTrait
{
    /**
     * @throws ReflectionException
     * @throws RuntimeException
     */
    public static function getDTO(array $param): AbstractBasic
    {
        parent::getDTO($param);
        $reflectClass = ReflectionManager::reflectClass(self::class);
        $docComment   = $reflectClass->getDocComment();
        return Helper::copyParamByClass($param, static::class, $docComment);
    }
}