<?php
declare (strict_types=1);

namespace tp\mytool\test;

use ReflectionException;
use PHPUnit\Framework\TestCase;
use tp\mytool\package\framework\exception\RuntimeException;
use tp\mytool\test\dto\DemoParamDTO;

class Test extends TestCase
{
    /**
     * @throws ReflectionException
     * @throws RuntimeException
     */
    public function demo()
    {
        $param = [
            'id'   => 1,
            'name' => 'demo',
        ];
        $dto   = DemoParamDTO::getDTO($param);

        $this->assertSame($param, $dto->toArray());
    }
}
