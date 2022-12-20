<?php
declare (strict_types=1);

namespace tp\mytool\package\framework\exception;

use Exception;

/**
 * 处理请求参数异常
 */
class RuntimeException extends Exception
{
    protected $raw;

    public function __construct($message = '', $code = 0, $raw = [])
    {
        parent::__construct($message, intval($code));
        $this->raw = $raw;
    }

    /**
     * @return array
     */
    public function getRaw(): array
    {
        return $this->raw;
    }
}
