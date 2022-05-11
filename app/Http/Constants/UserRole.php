<?php

namespace App\Http\Constants;

class UserRole
{

    const ROLE_ADMIN = 1;
    const ROLE_STAFF = 2;
    const ROLE_VIEW = 3;
    const ROLE_EDITOR = 4;

    const USER_ROLE_TEXT_EN = [
        '1' => 'Admin',
        '2' => 'Staff',
        '3' => 'View',
        '4' => 'editor'
    ];
    const USER_GANDER_TEXT_EN = [
        '1' => 'male',
        '2' => 'female'
    ];
}
