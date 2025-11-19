<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AdminUserService;
use App\Enums\Http;
use App\Models\User;

class AdminUserController extends Controller
{
    use \App\Http\Controllers\Concerns\ApiResponse;
    public function __construct(protected AdminUserService $adminuserService)
    {

    }
    public function index(Request $request)
    {
        $users = $this->adminuserService->paginateUsers($request);
        return $this->paginated($users);
    }

    public function show(string $id)
    {
        $user = $this->adminuserService->getUserById($id);

        if (!$user) {
            return $this->notFound('User not found');
        }
        return $this->successWithData($user);
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'role' => 'required|string|in:user,talent,admin,superadmin',
            'status' => 'required|string|in:active,inactive,banned',
        ]);
        $updatedUser = $this->adminuserService->updateUser($id, $validated);
        if (!$updatedUser) {
            return $this->notFound('User not found');
        }
        return $this->successWithData($updatedUser, 'User updated successfully');
    }

    public function destroy(string $id)
    {
        $deleted = $this->adminuserService->softDeleteUser($id);
        if (!$deleted) {
            return $this->notFound('User not found');
        }
        return $this->success('User deleted successfully', Http::OK);
    }

    public function restore(string $id)
    {
        $restored = $this->adminuserService->restoreUser($id);

        if (!$restored) {
            return $this->notFound('User not found or not deleted');
        }
        return $this->success('User restored successfully');
    }

    public function impersonate(string $id)
    {
        $session = $this->adminuserService->startImpersonation($id);

        if (!$session) {
            return $this->notFound('User not found');
        }
        return $this->successWithData($session, 'Impersonation started successfully');
    }
}
