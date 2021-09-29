<?php
namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait SortableTrait
{

    public function scopeSort(Builder $query, Request $request)
    {
        $sortables = data_get($this, 'sortables', []);

        $sort = $request->get('orderby');
        $direction = $request->get('order', 'desc');
        if ($sort
            && in_array($sort, $sortables)
            && $direction
            && in_array($direction, ['asc', 'desc'])) {
            if($request->orderby == 'status') {
                return $query->whereHas('entry', function($query) use ($direction) {
                    $query->orderBy('status', $direction);
                });
            } else {
                return $query->orderBy($sort, $direction);
            }
        }
        return $query;
    }
}
