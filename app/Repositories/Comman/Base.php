<?php


namespace App\Repositories\Comman;


use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class Base
{
    /**
     * 对页面进行分页处理
     * @param array $list
     * @param $request
     * @param int $perPage
     * @return array|LengthAwarePaginator
     */
    public function paginator(array $list, $request, $perPage = 15)
    {
        if ($request->has('page')) {
            $current_page = $request->input('page');
            $current_page = $current_page <= 0 ? 1 :$current_page;
        } else {
            $current_page = 1;
        }

        $item = array_slice($list, ($current_page-1) * $perPage, $perPage); //注释1
        $total = count($list);
        $list = new LengthAwarePaginator($item, $total, $perPage, $current_page, [
            'path' => Paginator::resolveCurrentPath(),  //注释2
            'pageName' => 'page',
        ]);
        return $list;
    }
}