<?php
declare (strict_types=1);

namespace tp\mytool\package\dao;

use Exception;
use think\Collection;
use think\Model;
use think\Paginator;

abstract class AbstractBasic
{
    /**
     * 分页
     * @var int
     */
    protected $page = Enum::PAGE;

    /**
     * 每页条数
     * @var int
     */
    protected $pageSize = Enum::PAGE_SIZE;

    /**
     * @var array
     */
    protected static $instance = [];

    /**
     * @var Model
     */
    protected $entity;

    public function __construct()
    {
        $this->entity = self::getEntityInstance();
    }

    abstract static protected function getEntity(): string;

    /**
     * @param int $page
     *
     * @return AbstractBasic
     */
    public function setPage(int $page): AbstractBasic
    {
        Paginator::currentPageResolver(function ($varPage = 'page') {
            return $this->page;
        });

        $this->page = $page;
        return $this;
    }

    /**
     * @param int $pageSize
     *
     * @return AbstractBasic
     */
    public function setPageSize(int $pageSize): AbstractBasic
    {
        $this->pageSize = $pageSize;
        return $this;
    }

    public function save(Model $model): bool
    {
        return $model->save();
    }

    /**
     * @throws Exception
     */
    public function saveAll(Model $model, array $data): Collection
    {
        return $model->saveAll($data);
    }

    public function delete(Model $model): bool
    {
        return $model->delete();
    }

    /**
     * @return AbstractBasic
     */
    public static function getDAOInstance(): AbstractBasic
    {
        if (!isset(static::$instance['dao'][static::class])) {
            static::$instance['dao'][static::class] = new static();
        }

        return static::$instance['dao'][static::class];
    }

    /**
     * @return Model
     */
    public static function getEntityInstance(): Model
    {
        $entity = static::getEntity();
        return new $entity();
    }
}