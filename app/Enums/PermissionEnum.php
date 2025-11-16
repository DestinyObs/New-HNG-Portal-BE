<?php

namespace App\Enums;

use App\Enums\RoleEnum;

enum PermissionEnum: string
{
    //role
     case View_Role = 'role.view';
     case Create_Role = 'role.create';
     case Update_Role = 'role.update';
     case Delete_Role = 'role.delete';

    // Admin
    case View_Admin = 'admin.view';
    case Create_Admin = 'admin.create';
    case Update_Admin = 'admin.update';
    case Delete_Admin = 'admin.delete';

    // Company
    case View_Company = 'company.view';
    case Create_Company = 'company.create';
    case Update_Company = 'company.update';
    case Delete_Company = 'company.delete';

    // Skill
    case View_Skill = 'skill.view';
    case Create_Skill = 'skill.create';
    case Update_Skill = 'skill.update';
    case Delete_Skill = 'skill.delete';

    // Job Listing
    case View_JobListing = 'joblisting.view';
    case Create_JobListing = 'joblisting.create';
    case Update_JobListing = 'joblisting.update';
    case Delete_JobListing = 'joblisting.delete';

    // Talent Verification
    case Verify_Talent = 'talent.verify_document';


    /**
     * Return default permissions for a given role as enum cases.
     */
    public static function getPermissionsFor(RoleEnum $role): array
    {
        return match ($role) {
            RoleEnum::ADMIN => self::cases(),

            RoleEnum::EMPLOYER => [
                self::View_Company,
                self::Create_Company,
                self::Update_Company,
                self::View_JobListing,
                self::Create_JobListing,
                self::Update_JobListing,
                self::Delete_JobListing,
            ],

            RoleEnum::TALENT => [
                self::View_Skill
            ],
        };
    }
}
