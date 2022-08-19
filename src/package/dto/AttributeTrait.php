<?php
declare(strict_types=1);

namespace tp\mytool\package\dto;

trait AttributeTrait
{
    /**
     * 原始数据
     * @var array
     */
    protected $origin = [];

    /**
     * 当前数据
     * @var array
     */
    protected $data = [];

    /**
     * @return array
     */
    public function getOrigin(): array
    {
        return $this->origin;
    }

    /**
     * @param array $origin
     *
     * @return void
     */
    public function setOrigin(array $origin): void
    {
        $this->origin = $origin;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}