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
    const MATCH_DTO_DOC_FIELD_TYPE = '/[\w|\w\|]+(?=\s*\$%s)/';

    /**
     * 匹配手机号
     */
    const MATCH_MOBILE = '/^1[3-9]\d{9}$/';

    /**
     * 匹配身份证号
     */
    const MATCH_ID_CARD = '/(^[1-9]\d{5}(18|19|([23]\d))\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$)|(^[1-9]\d{5}\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}$)/';
}