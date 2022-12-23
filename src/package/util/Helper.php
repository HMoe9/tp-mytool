<?php
declare(strict_types=1);

namespace tp\mytool\package\util;

use tp\mytool\package\dto\AbstractBasic;
use tp\mytool\package\framework\di\ReflectionManager;
use PhpDocReader\PhpParser\UseStatementParser;
use ReflectionException;
use tp\mytool\package\framework\exception\RuntimeException;

class Helper
{
    /**
     * param 转 dto
     *
     * @param array $param
     * @param string $className
     * @param string $docComment
     * @param bool $newInstance
     *
     * @return AbstractBasic
     * @throws ReflectionException
     * @throws RuntimeException
     */
    public static function copyParamByClass(array $param, string $className, string $docComment = '', bool $newInstance = false): AbstractBasic
    {
        self::dependCheck($className);

        $reflectionClass = ReflectionManager::reflectClass($className);
        if (empty($docComment)) {
            $docComment = $reflectionClass->getDocComment();
        }

        /** @var AbstractBasic $instance */
        $instance = ReflectionManager::getInstance($className, $newInstance);

        $pregMatchAll = preg_match_all(Regex::MATCH_DTO_DOC_FIELD, $docComment, $matchFieldList);
        if ($pregMatchAll > 0) {
            $fieldList = current($matchFieldList);

            foreach ($fieldList as $field) {
                if (!isset($param[$field])) {
                    continue;
                }

                $value     = $param[$field];
                $pregMatch = preg_match(sprintf(Regex::MATCH_DTO_DOC_FIELD_TYPE, $field), $docComment,
                    $matchFieldTypeList);
                if ($pregMatch > 0) {
                    $fieldType = current($matchFieldTypeList);

                    // 移除 null 标识
                    if (stripos($fieldType, '|null') !== false) {
                        $fieldType = str_replace('|null', '', $fieldType);
                    }

                    $namespaceName   = $reflectionClass->getNamespaceName();
                    $dependClassName = sprintf('%s\\%s', $namespaceName, $fieldType);
                    if (class_exists($dependClassName)) {
                        self::dependCheck($dependClassName);

                        /** @var AbstractBasic $dependClassName */
                        $value = $dependClassName::getDTO($value, $newInstance);
                    } else {
                        $lowerFieldType     = strtolower($fieldType);
                        $useStatementParser = new UseStatementParser();
                        $useList            = $useStatementParser->parseUseStatements($reflectionClass);
                        if (isset($useList[$lowerFieldType])) {
                            $dependClassName = $useList[$lowerFieldType];
                            self::dependCheck($dependClassName);

                            /** @var AbstractBasic $dependClassName */
                            $value = $dependClassName::getDTO($value, $newInstance);
                        } else {
                            $value = self::parse($value, $fieldType);
                        }
                    }
                }

                $instance->$field = $value;
            }

            $instance->setOrigin($instance->getData());
        }

        return $instance;
    }

    /**
     * param 转 dto
     *
     * @param array $param
     * @param AbstractBasic $instance
     * @param string $docComment
     * @param bool $newInstance
     *
     * @return AbstractBasic
     * @throws ReflectionException
     * @throws RuntimeException
     */
    public static function copyParamByInstance(array $param, AbstractBasic $instance, string $docComment = '', bool $newInstance = false): AbstractBasic
    {
        $className = get_class($instance);
        return static::copyParamByClass($param, $className, $docComment, $newInstance);
    }

    /**
     * @param string $className
     *
     * @throws ReflectionException
     * @throws RuntimeException
     */
    private static function dependCheck(string $className): void
    {
        $reflectionClass = ReflectionManager::reflectClass($className);
        $isInstantiable  = $reflectionClass->isInstantiable();
        if (!$isInstantiable) {
            throw new RuntimeException(sprintf('%s 不是一个可实例化的类', $className));
        }

        if (!is_subclass_of($className, AbstractBasic::class)) {
            throw new RuntimeException(sprintf('%s 必须继承或者实现 %s 类', $className, AbstractBasic::class));
        }
    }

    /**
     * 对应类型处理
     *
     * @param $value
     * @param string $fieldType 字段类型,多个 '|' 分割 ex: int|null
     *
     * @return mixed
     */
    private static function parse($value, string $fieldType)
    {
        if (stripos($fieldType, 'string') !== false) {
            // 如果指定类型为字符串,强转字符串并去左右空格,
            $value = trim(strval($value));
        } elseif (stripos($fieldType, 'int') !== false) {
            $value = intval($value);
        }

        return $value;
    }
}