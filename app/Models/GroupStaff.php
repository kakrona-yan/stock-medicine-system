<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Constants\DeleteStatus;

class GroupStaff extends BaseModel
{
    protected $table = 'group_staffs';

    protected $fillable = [
        'name',
        'is_active',
        'is_delete'
    ];

    public function filter($request)
    {
        $groupStaffs = $this->where('is_delete', '<>', DeleteStatus::DELETED)
                    ->orderBy('created_at', 'DESC');
        $limit = config('pagination.limit');
        return $groupStaffs->paginate($limit, ['*'], 'group-staff-page');
    }

    public function getGroupStaffName()
    {
        return $this->pluck('name','id')
            ->all();
    }
}
