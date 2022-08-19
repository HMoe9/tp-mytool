<?php
declare(strict_types=1);

namespace tp\mytool\package\dto;

use Closure;
use think\exception\ValidateException;

trait ValidateBuiltinTypeTrait
{
    /**
     * 字符串类型字段校验
     *
     * @author HMoe9 <hmoe9@qq.com>
     *
     * @param $field
     *
     * @return Closure
     */
    protected static function stringValidate($field): Closure
    {
        return function ($value, $data) use ($field) {

            if (!is_null($value) && !is_string($value)) {
                throw new ValidateException(sprintf('%s 字段值必须是一个字符串', $field));
            }

            return true;
        };
    }
}