<?php

namespace App\Constants;

class Users
{
    public const USER_TYPE_SUPER_ADMIN = 'super_admin';
    public const USER_TYPE_ADMIN = 'admin';
    public const USER_TYPE_EMPLOYEE = 'employee';

    public const VALID_USER_TYPES = [
        self::USER_TYPE_SUPER_ADMIN,
        self::USER_TYPE_ADMIN,
        self::USER_TYPE_EMPLOYEE,
    ];
}
