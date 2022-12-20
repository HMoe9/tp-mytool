<?php
declare(strict_types=1);

namespace tp\mytool\package\framework\collection;

use think\Paginator;

trait Paginate
{
    /**
     * 转数组
     *
     * @param Paginator $paginator
     *
     * @return array
     */
    protected function toArray(Paginator $paginator): array
    {
        return [
            'list'         => $paginator->items(),
            'per_page'     => $paginator->listRows(),
            'current_page' => $paginator->currentPage(),
            'last_page'    => $paginator->lastPage(),
            'total'        => $paginator->total(),
        ];
    }
}