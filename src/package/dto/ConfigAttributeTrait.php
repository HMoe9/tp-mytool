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
     * @param array $param
     * @param bool $newInstance
     *
     * @return AbstractBasic
     * @throws ReflectionException
     * @throws RuntimeException
     */
    public static function getDTO(array $param, bool $newInstance = false): AbstractBasic
    {
        $getParentClass = get_parent_class(parent::class);
        if (empty($getParentClass)) {
            throw new RuntimeException(sprintf('%s 必须是 %s 的实例', parent::class, AbstractBasic::class));
        }

        $parentClassList   = [];
        $parentClassList[] = $getParentClass;
        $recursionDepth    = 10; // 最大查询深度
        while ($getParentClass = get_parent_class($getParentClass)) {

            $parentClassList[] = $getParentClass;
            $recursionDepth--;

            if ($recursionDepth <= 0) {
                if (!in_array(AbstractBasic::class, $parentClassList)) {
                    throw new RuntimeException('超出最大查询深度');
                }

                break;
            }
        }

        if (!in_array(AbstractBasic::class, $parentClassList)) {
            throw new RuntimeException(sprintf('%s 必须是 %s 的实例', parent::class, AbstractBasic::class));
        }

        $DTO = parent::getDTO($param);
        if (self::class !== static::class) {
            $reflectClass = ReflectionManager::reflectClass(self::class);
            $docComment   = $reflectClass->getDocComment();
            $DTO          = Helper::copyParamByClass($param, static::class, $docComment, $newInstance);
        }
        return $DTO;
    }
}