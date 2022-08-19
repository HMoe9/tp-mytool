<?php
declare (strict_types = 1);

namespace tp\mytool\package;

use tp\mytool\package\framework\lang\ZhCn;

class Service extends \think\Service
{
    public function register()
    {
        $this->app->bind('tp-mytool.lang', ZhCn::class);
    }

    public function boot()
    {

    }
}
