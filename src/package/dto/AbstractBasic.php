<?php
declare(strict_types=1);

namespace tp\mytool\package\dto;

use ArrayAccess;
use think\contract\Arrayable;

abstract class AbstractBasic implements Arrayable, ArrayAccess
{
    use AttributeTrait;

    /**
     * param 数组转 dto 对象
     *
     * @param array $param
     * @param bool $newInstance
     *
     * @return AbstractBasic
     */
    abstract public static function getDTO(array $param, bool $newInstance = false): AbstractBasic;

    /**
     * dto 转数组
     *
     * @return array
     */
    public function toArray(): array
    {
        $data = $this->getData();
        foreach ($data as &$value) {
            $value = $this->nestedToArray($value);
        }
        return $data;
    }

    /**
     * 嵌套数组 dto 对象转化
     *
     * @param $nestedValue
     *
     * @return array|mixed
     */
    private function nestedToArray($nestedValue)
    {
        if (is_array($nestedValue)) {
            foreach ($nestedValue as &$value) {
                $value = $this->nestedToArray($value);
            }
        } elseif ($nestedValue instanceof AbstractBasic) {
            $nestedValue = $nestedValue->toArray();
        }

        return $nestedValue;
    }

    protected function set($key, $value): void
    {
        $this->data[$key] = $value;
    }

    protected function get($key)
    {
        if ($this->has($key)) {
            return $this->data[$key];
        }
        return null;
    }

    protected function has($key): bool
    {
        return isset($this->data[$key]);
    }

    public function offsetExists($offset): bool
    {
        return $this->has($offset);
    }

    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    public function offsetSet($offset, $value): void
    {
        $this->set($offset, $value);
    }

    public function offsetUnset($offset)
    {
        // ...
    }

    public function __set($name, $value): void
    {
        $this->set($name, $value);
    }

    public function __get($name)
    {
        return $this->get($name);
    }

    public function __isset($name): bool
    {
        return $this->has($name);
    }
}