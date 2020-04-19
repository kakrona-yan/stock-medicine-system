<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Http\Constants\UserRole;
use App\Http\Constants\DeleteStatus;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email', 
        'password',
        'role',
        'thumbnail',
        'is_active',
        'is_delete'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function staff()
    {
        return $this->hasOne('App\Models\Staff', 'user_id', 'id');
    }

    public function roleType()
    {
        $role = $this->role;
        $roleText = '';
        if (is_null($role) && empty($role)) return;
        switch ($role) {
            case 1:
                $roleText = UserRole::USER_ROLE_TEXT_EN[$role];
                break;
            case 2:
                $roleText = UserRole::USER_ROLE_TEXT_EN[$role];
                break;
            case 3:
                $roleText = UserRole::USER_ROLE_TEXT_EN[$role];
                break;
            case 4:
                $roleText = UserRole::USER_ROLE_TEXT_EN[$role];
                break;
    }
        return $roleText;
    }

    public function isRoleAdmin()
    {
        return $this->role == UserRole::ROLE_ADMIN ? true : false;
    }

    public function isRoleStaff()
    {
        return $this->role == UserRole::ROLE_STAFF ? true : false;
    }

    public function isRoleView()
    {
        return $this->role == UserRole::ROLE_VIEW ? true : false;
    }

    public function isRoleEditor()
    {
        return $this->role == UserRole::ROLE_EDITOR ? true : false;
    }

    public function filter($request)
    {
        $users = $this->where('is_delete', '<>', DeleteStatus::DELETED)
            ->orderBy('id', 'DESC');
        // filter by username
        if ($request->exists('name') && !empty($request->name)) {
            $users->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->exists('email') && !empty($request->email)) {
            $users->where('email', 'like', '%' . $request->email . '%');
        }
        // filter by username
        if ($request->exists('role') && !is_null($request->role)) {
            $users->where('role', $request->role);
        }
        $limit = config('pagination.limit');
        if ($request->exists('limit') && !is_null($request->limit)) {
            $limit = $request->limit;
        }
        // Check flash danger
        flashDanger($users->count(), __('flash.empty_data'));
        return $users->paginate($limit, ['*'], 'user-page');
    }

    public function available($id)
    {
        return $this->where('id',  $id)
            ->where('is_delete', '<>', DeleteStatus::DELETED)
            ->first();
    }

    public function remove()
    {
        return $this->update([
            'is_delete' => DeleteStatus::DELETED
        ]);
    }
}
