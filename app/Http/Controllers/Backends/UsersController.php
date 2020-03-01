<?php

namespace App\Http\Controllers\Backends;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Constants\UserRole;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\Staff;

class UsersController extends Controller
{
    public function __construct(
        User $user,
        Staff $staff
    ){
        $this->user = $user;
        $this->staff = $staff;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $users = $this->user->filter($request);
            $userRoles = UserRole::USER_ROLE_TEXT_EN;
            return view('backends.users.index', [
                'request' => $request,
                'users' => $users,
                'userRoles' => $userRoles
            ]);
        } catch (\ValidationException $e) {
            return exceptionError($e, 'backends.users.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
            $userRoles = UserRole::USER_ROLE_TEXT_EN;
            return view('backends.users.create', [
                'request' => $request,
                'userRoles' => $userRoles
            ]);
        } catch (\ValidationException $e) {
            return exceptionError($e, 'backends.users.create');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserCreateRequest $request)
    {
        try {
            $user = $request->all();
            $user['email_verified_at'] = now();
            $user['password'] =  bcrypt($user['password']);
            $user['thumbnail'] = isset($user['thumbnail']) ? uploadFile($user['thumbnail'], config('upload.user')) : '';
            $saveUser = $this->user->create($user);
            if($saveUser) {
                $staffRequest = [];
                if ($request->exists('thumbnail') && !empty($user['thumbnail'])) {
                    $user['thumbnail'] = uploadFile($user['thumbnail'], config('upload.staff'));
                }
                $staffRequest['user_id'] = $saveUser->id;
                $staffRequest['name'] = $saveUser->name;
                $staffRequest['email'] = $user['email'];
                $staffRequest['password'] = $request->password;
                $staffRequest['is_delete'] = $saveUser->role == 1 || $saveUser->role == 3 || $saveUser->role == 4 ? 0 : 1;
                $this->staff->create($staffRequest);
            }
            return \Redirect::route('user.index')
                ->with('success', __('flash.store'));
        } catch (\ValidationException $e) {
            return exceptionError($e, 'backends.users.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        try {
            $user = $this->user->available($id);
            if (!$user) {
                return response()->view('errors.404', [], 404);
            }
            return view('backends.users.show', [
                'user' => $user,
            ]);
        } catch (\ValidationException $e) {
            return exceptionError($e, 'backends.users.show');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        try {
            $user = $this->user->available($id);
            if (!$user) {
                return response()->view('errors.404', [], 404);
            }
            return view('backends.users.edit', [
                'user' => $user
            ]);
        } catch (\ValidationException $e) {
            return exceptionError($e, 'backends.users.edit');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, $id)
    {
        try {
            $requestUser = $request->all();
            $user = $this->user->available($id);
            if (!$user) {
                return response()->view('errors.404', [], 404);
            }
            if ($request->password && !empty($request->password)) {
                $requestUser['password'] =  bcrypt($request->password);
            } else {
                unset($requestUser['password']);
            }
            if (!empty($request->thumbnail)) {
                $requestUser['thumbnail'] = uploadFile($request->thumbnail, config('upload.user'));
            } else {
                unset($requestUser['thumbnail']);
            }
            $user->update($requestUser);
            if($user) {
                $staffRequest = [];
                if ($request->exists('thumbnail') && !empty($user['thumbnail'])) {
                    $user['thumbnail'] = uploadFile($user['thumbnail'], config('upload.staff'));
                }
                $staffRequest['user_id'] = $user->id;
                $staffRequest['name'] = $user->name;
                $staffRequest['email'] = $requestUser['email'];
                if ($request->password && !empty($request->password)) {
                    $staffRequest['password'] = $request->password;
                } 
                $staffRequest['is_delete'] = $user->role == 1 || $user->role == 3 || $user->role == 4 ? 0 : 1;

                $user->staff()->update($staffRequest);
            }
            return \Redirect::route('user.index')
                ->with('warning', __('flash.update'));
        } catch (\ValidationException $e) {
            return exceptionError($e, 'backends.users.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try {
            $id = $request->user_id;
            $user = $this->user->available($id);
            if (!$user) {
                return response()->view('errors.404', [], 404);
            }
            $user->staff()->remove();
            $user->remove();
            return redirect()->route('user.index')
                ->with('danger', __('flash.destroy'));
        } catch (\ValidationException $e) {
            return exceptionError($e, 'backends.users.index');
        }
    }
}
