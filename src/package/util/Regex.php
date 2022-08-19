<?php
declare(strict_types=1);

namespace tp\mytool\package\util;

final class Regex
{
    /**
     * 匹配 dto 注释里的字段
     * ex:
     * @property-read int $id
     * 匹配 id
     */
    const MATCH_DTO_DOC_FIELD = '/(?<=\$)\w+/';

    /**
     * 匹配 dto 注释里的字段类型
     * %s 占位符 需要使用 sprintf 替换为具体的字段名
     * ex:
     * @property-read int $id
     * @property-read string|null $name
     * 匹配 int 或 string|null
     */
    const MATCH_DTO_DOC_BUILTIN_TYPE = '/[\w|\w\|]+(?=\s*\$%s)/';
}