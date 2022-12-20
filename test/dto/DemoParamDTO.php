<?php
declare(strict_types=1);

namespace tp\mytool\test\dto;

use tp\mytool\package\dto\AbstractParamDTO;
use tp\mytool\package\dto\ValidateBuiltinTypeTrait;

/**
 * Class DemoParamDTO
 *
 * @property-read int $id
 * @property-read string $name
 * @package tp\mytool\test\dto
 */
class DemoParamDTO extends AbstractParamDTO
{
    use ValidateBuiltinTypeTrait;

    protected static function validateRule(): array
    {
        return [
            'id'   => ['require'],
            'name' => ['require', self::stringValidate('name')],
        ];
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}