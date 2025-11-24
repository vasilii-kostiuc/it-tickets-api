<?php

namespace App\Http\Controllers\Rbac;

use App\Http\Controllers\Controller;
use App\Http\Requests\Rbac\StoreRoleRequest;
use App\Http\Requests\Rbac\UpdateRolePermissionsRequest;
use App\Http\Requests\Rbac\UpdateRoleRequest;
use App\Services\Rbac\RoleService;
use App\Models\Rbac\Permission;
use App\Models\Rbac\Role;
use Illuminate\Http\RedirectResponse;

class RoleController extends Controller
{
    private RoleService $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function index()
    {
        if (!auth()?->user()?->can('roles.view')) {
            return response()->redirectToRoute('dashboard');
        }

        return view('list', [
            'title' => __("Roles"),
            'attributes' => ['name' => "Role name", 'description' => 'Description'],
            'models' => Role::all(),
        ]);
    }

    public function create()
    {
        if (!auth()?->user()?->can('roles.create')) {
            return response()->redirectToRoute('dashboard');
        }

        $permissions = Permission::all();
        return view('rbac.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request): RedirectResponse
    {
        if (!auth()?->user()?->can('roles.create')) {
            return response()->redirectToRoute('dashboard');
        }

        $attributes = $request->safe()->except('permissions');
        $permissions = ($request->safe(['permissions'])['permissions']) ?? [];

        $this->roleService->create($attributes, $permissions);

        return redirect()
            ->route("roles.index")
            ->with("status-success", "Role created");
    }

    public function show(Role $role)
    {
        if (!auth()?->user()?->can('roles.view')) {
            return response()->redirectToRoute('dashboard');
        }

        $permissions = Permission::all();
        $disabled = true;

        return view('rbac.roles.edit', compact('role', 'permissions', 'disabled'));
    }

    public function edit(Role $role)
    {
        if (!auth()?->user()?->can('roles.update')) {
            return response()->redirectToRoute('dashboard');
        }

        $permissions = Permission::all();
        $disabled = false;
        return view('rbac.roles.edit', compact('role', 'permissions', 'disabled'));
    }

    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        if (!auth()?->user()?->can('roles.update')) {
            return response()->redirectToRoute('dashboard');
        }

        $this->roleService->update($role, $request->validated());

        return redirect()
            ->route("roles.index")
            ->with("status-success", "Role updated");
    }

    public function updateRolePermissions(UpdateRolePermissionsRequest $request, Role $role): RedirectResponse
    {
        if (!auth()?->user()?->can('roles.update')) {
            return response()->redirectToRoute('dashboard');
        }

        $permissions = [];
        $permissionsRequest = ($request->safe(['permissions'])['permissions']) ?? [];
        foreach ($permissionsRequest as $permission) {
            $permissions[] = (int)$permission;
        }

        $this->roleService->updateRolePermissions($role, $permissions);

        return redirect()->route("roles.index")->with("status-success", "Role permissions updated");
    }

    public function destroy(Role $role): RedirectResponse
    {
        try {
            if (!auth()?->user()?->can('roles.delete')) {
                return response()->redirectToRoute('dashboard');
            }

            $this->roleService->remove($role);
        } catch (\Exception $ex) {
            return redirect()
                ->route("roles.index")
                ->with("status-error", __($ex->getMessage()));
        }

        return redirect()
            ->route("roles.index")
            ->with("status-success", "Role deleted");
    }
}
