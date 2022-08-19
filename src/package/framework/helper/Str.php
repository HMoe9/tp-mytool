<?php
namespace tp\mytool\package\framework\helper;

class Str extends \think\helper\Str
{
    /**
     * 首字母大写
     */
    public static function ucfirst(string $string): string
    {
        return static::upper(static::substr($string, 0, 1)) . static::substr($string, 1);
    }
}
