<?php

namespace App\Http\Constants;

class DeleteStatus
{
    // Delete Status
    const NOT_DELETED = 1;
    const DELETED = 0;

    const DELETE_TYPE_TEXT = [
        '0' => 'delete',
        '1' => 'no delete',
    ];
}
