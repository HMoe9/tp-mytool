<?php
declare(strict_types=1);

namespace tp\mytool\package\framework\lang;

class ZhCn
{
    public function getLangFile(): array
    {
        return [
            sprintf('%s/zh-cn.php', __DIR__)
        ];
    }
}