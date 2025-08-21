<?php

namespace App\Core;

class Constants
{
    // Status constants
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    const STATUS_PENDING = 2;

    // User roles
    const ROLE_ADMIN = 'admin';
    const ROLE_DOSEN = 'dosen';
    const ROLE_ADMIN_PRODI = 'admin_prodi';

    // Pagination
    const DEFAULT_PER_PAGE = 10;
    const MAX_PER_PAGE = 100;

    // Array constants
    public static function getStatusList()
    {
        return [
            self::STATUS_ACTIVE => 'Aktif',
            self::STATUS_INACTIVE => 'Tidak Aktif',
            self::STATUS_PENDING => 'Pending',
        ];
    }

    public static function getRoleList()
    {
        return [
            self::ROLE_ADMIN => 'Administrator',
            self::ROLE_DOSEN => 'Dosen',
            self::ROLE_ADMIN_PRODI => 'Admin Prodi',
        ];
    }
}
