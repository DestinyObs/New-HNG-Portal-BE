<?php

namespace App\Enums;

enum RoleEnum: string
{
    case ADMIN = 'admin';
    case TALENT = 'talent';
    case EMPLOYER = 'employer';

    public function defaultPermissions(): array
    {
        return PermissionEnum::getPermissionsFor($this);
    }
}
