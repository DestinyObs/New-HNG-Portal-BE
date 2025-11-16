<?php

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    public function up(): void
    {
        $this->createDefaultRolesAndPermissions();
    }

    public function down(): void
    {
        //
    }

    private function createDefaultRolesAndPermissions(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissionModel = config('permission.models.permission');
        $roleModel = config('permission.models.role');

        // ------------------------------
        // Seed permissions
        // ------------------------------
        foreach (PermissionEnum::cases() as $permissionEnum) {
            $permissionModel::firstOrCreate(['name' => $permissionEnum->value]);
        }

        // ------------------------------
        // Seed roles and assign default permissions
        // ------------------------------
        foreach (RoleEnum::cases() as $roleEnum) {
            $role = $roleModel::firstOrCreate(['name' => $roleEnum->value]);
            $role->syncPermissions($roleEnum->defaultPermissions());
        }
    }
};
