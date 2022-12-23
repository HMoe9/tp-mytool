<?php
declare (strict_types=1);

namespace tp\mytool\tests;

use ReflectionException;
use PHPUnit\Framework\TestCase;
use tp\mytool\package\framework\exception\RuntimeException;
use tp\mytool\tests\dto\DemoParamDTO;

class DTOTest extends TestCase
{
    /**
     * @throws ReflectionException
     * @throws RuntimeException
     */
    public function testDemo()
    {
        $param = [
            'id'   => 1,
            'name' => 'demo',
        ];
        $dto   = DemoParamDTO::getDTO($param);

        $this->assertSame($param, $dto->toArray());
    }
}
