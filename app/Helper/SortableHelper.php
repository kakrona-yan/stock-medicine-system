<?php

namespace App\Helper;

use App\Constants\ExhibitionConstant;
use Illuminate\Support\Facades\Route;

class SortableHelper
{
    public static function order(string $title, string $orderBy, string $route = '')
    {
        $request = request();
        if (!$route) {
            $route = $request->route()->getName();
        }
        $order      = 'asc';
        $iconClass  = '';
        $reqOrder   = $request->get('order');
        $reqOrderBy = $request->get('orderby');
        if ($reqOrder && $reqOrderBy && $reqOrderBy == $orderBy) {
            if ($reqOrder === 'asc') {
                $iconClass = 'icon-active-up';
                $order = 'desc';
            } else {
                $iconClass = 'icon-active-down';
                $order = 'asc';
            }
        }
        $params = [ 'orderby' => $orderBy, 'order' => $order];
        if ($request->exists('status')) {
            $params['status'] = $request->status;
        }
        if ($request->type) {
            $params['type'] = $request->type;
        }
        $request = $request->all();
        if(isset($request['s'])){
            unset($request['s']);
        }
        $current = Route::current();
        $status = $current->parameters();
        $args = array_merge($request, $params);
        if(isset($status['status'])) {
            $isStatus = isset(ExhibitionConstant::ROUTE_STATUS[$status['status']])?? null;
            if($isStatus){
                $args = array_merge($args, $status);
            }
        }

        return '<a href="'. route($route, $args) .'" class="link-order '. $iconClass .'">' .$title .'<i class="fa icon-sort '. $iconClass .' fa-lg" aria-hidden="true"></i></a>';
    }
}
