<?php
declare(strict_types=1);

namespace tp\mytool\package\util;

use tp\mytool\package\dto\AbstractBasic;
use tp\mytool\package\framework\di\ReflectionManager;
use ReflectionException;
use tp\mytool\package\framework\exception\RuntimeException;

class Helper
{
    /**
     * param 转 dto
     *
     * @author HMoe9 <hmoe9@qq.com>
     *
     * @param array $param
     * @param string $className
     * @param string $docComment
     *
     * @return AbstractBasic
     * @throws RuntimeException
     * @throws ReflectionException
     */
    public static function copyParamByClass(array $param, string $className, string $docComment = ''): AbstractBasic
    {
        if (empty($docComment)) {
            $reflectionClass = ReflectionManager::reflectClass($className);
            $docComment      = $reflectionClass->getDocComment();
        }

        /** @var AbstractBasic $instance */
        $instance = ReflectionManager::getInstance($className);
        if (!($instance instanceof AbstractBasic)) {
            throw new RuntimeException(sprintf('类必须是 %s 的实例', AbstractBasic::class));
        }

        if (!empty($docComment)) {
            $pregMatchAll = preg_match_all(Regex::MATCH_DTO_DOC_FIELD, $docComment, $matchField);
            if ($pregMatchAll > 0) {
                $fieldList = current($matchField);

                foreach ($fieldList as $field) {
                    if (isset($param[$field])) {
                        $value       = $param[$field];
                        $pregMatch   = preg_match(sprintf(Regex::MATCH_DTO_DOC_BUILTIN_TYPE, $field), $docComment, $matchBuiltinType);
                        if ($pregMatch > 0) {
                            $builtinType = current($matchBuiltinType);
                            $value = self::parse($value, $builtinType);
                        }

                        $instance->$field = $value;
                    }
                }

                $instance->setOrigin($instance->getData());
            }
        }

        return $instance;
    }

    /**
     * param 转 dto
     *
     * @author HMoe9 <hmoe9@qq.com>
     *
     * @param array $param
     * @param AbstractBasic $instance
     *
     * @return AbstractBasic
     * @throws RuntimeException
     * @throws ReflectionException
     */
    public static function copyParamByInstance(array $param, AbstractBasic $instance): AbstractBasic
    {
        $className = get_class($instance);
        return static::copyParamByClass($param, $className);
    }

    /**
     * 对应类型处理
     *
     * @author HMoe9 <hmoe9@qq.com>
     *
     * @param $value
     * @param string $builtinType 内置类型,多个 '|' 分割 ex: int|null
     *
     * @return mixed
     */
    private static function parse($value, string $builtinType)
    {
        if (stripos($builtinType, 'string') !== false) {
            // 如果指定类型为字符串,强转字符串并去左右空格,
            $value = trim(strval($value));
        } elseif (stripos($builtinType, 'int') !== false) {
            $value = intval($value);
        }

        return $value;
    }
}