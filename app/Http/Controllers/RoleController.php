<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\RoleResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends BaseController implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth:api',
            new Middleware('permission:role_create', only: ['store']),
            new Middleware('permission:role_edit', only: ['update']),
            new Middleware('permission:role_delete', only: ['destroy']),
            new Middleware('permission:role_show', only: ['show']),
            new Middleware('permission:role_index', only: ['index']),
        ];
    }

    public function index()
    {
        $roles = Role::with('permissions')->get();
        return response()->json(['roles' => RoleResource::collection($roles)]);
    }

    public function store(CreateRoleRequest $request)
    {
        $role = Role::create(['name' => $request->name]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return response()->json([
            'message' => 'Role created successfully',
            'role' => $role->load('permissions')
        ], 201);
    }

    public function show($id) {
        $role = Role::with('permissions')->find($id);
        if (!$role) {
            return $this->sendError('Role not found.', 404);
        }
        return $this->sendResponse(new RoleResource($role), 'Role retrieved successfully.');
    }

    public function update(UpdateRoleRequest $request, $id)
    {
        $role = Role::find($id);
        $role->update(['name' => $request->name]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return response()->json([
            'message' => 'Role updated successfully',
            'role' => $role->load('permissions')
        ]);
    }

    public function destroy($id)
    {
        $role = Role::find($id);
        if ($role->name === 'admin') {
            return response()->json(['message' => 'Cannot delete admin role'], 403);
        }

        $role->delete();
        return response()->json(['message' => 'Role deleted successfully']);
    }

    public function assignRole(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,name'
        ]);

        $user = User::findOrFail($request->user_id);

        $user->syncRoles($request->roles);

        return response()->json([
            'message' => 'Roles assigned successfully',
            'user' => $user->load('roles')
        ]);
    }

    public function permissions()
    {
        $permissions = Permission::all();
        return response()->json(['permissions' => $permissions]);
    }

}
