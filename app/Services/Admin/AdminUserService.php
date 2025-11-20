<?php

namespace App\Services\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminUserService
{
    public function paginateUsers($request)
    {
        return User::withTrashed()
            ->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 20));
    }

    public function getUserById(string $id)
    {
        return User::withTrashed()->find($id);
    }

    public function updateUser(string $id, array $data)
    {
        $user = User::withTrashed()->find($id);
        if (!$user) return false;

        // Handle role separately
        if (isset($data['role'])) {
            $user->syncRoles([$data['role']]);
            unset($data['role']); // remove so fill() doesn't break
        }

        // Apply all other updatable fields
        if (!empty($data)) {
            $user->fill($data);
        }

        $user->save();

        return $user->fresh();
    }


    public function softDeleteUser(string $id)
    {
        $user = User::withTrashed()->find($id);
        if (!$user) return false;

        return $user->delete();
    }

    public function restoreUser(string $id)
    {
        $user = User::withTrashed()->find($id);
        if (!$user || !$user->trashed()) return false;

        $user->restore();
        return true;
    }

    // public function startImpersonation(string $id)
    // {
    //     $user = User::withTrashed()->find($id);
    //     if (!$user) return false;

    //     session(['admin_impersonator_id' => Auth::id()]);
    //     Auth::login($user);

    //     return [
    //         'impersonating' => true,
    //         'user_id' => $user->id,
    //         'user' => $user
    //     ];
    // }
}
