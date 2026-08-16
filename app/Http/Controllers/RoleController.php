<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    /**
     * Define categorized list of permissions for UI organization.
     */
    public static function getPermissionGroups(): array
    {
        return [
            'Dashboard & System' => [
                'view dashboard',
                'view notifications',
                'view settings',
                'edit settings',
            ],
            'POS System' => [
                'access pos',
            ],
            'Kitchen Display' => [
                'view kitchen',
                'update kitchen status',
            ],
            'Order Management' => [
                'view orders',
                'create orders',
                'edit orders',
                'delete orders',
                'cancel orders',
            ],
            'Table & Reservations' => [
                'view tables',
                'create tables',
                'edit tables',
                'delete tables',
                'view reservations',
                'create reservations',
                'edit reservations',
                'delete reservations',
            ],
            'Menu & Categories' => [
                'view categories',
                'create categories',
                'edit categories',
                'delete categories',
                'view menu',
                'create menu',
                'edit menu',
                'delete menu',
            ],
            'Customer Management' => [
                'view customers',
                'create customers',
                'edit customers',
                'delete customers',
            ],
            'Employee & Attendance' => [
                'view employees',
                'create employees',
                'edit employees',
                'delete employees',
                'view attendance',
                'manage attendance',
            ],
            'Inventory & Stock' => [
                'view inventory',
                'create inventory',
                'edit inventory',
                'delete inventory',
                'adjust inventory',
            ],
            'Suppliers & Purchases' => [
                'view suppliers',
                'create suppliers',
                'edit suppliers',
                'delete suppliers',
                'view purchases',
                'create purchases',
                'edit purchases',
                'delete purchases',
            ],
            'Expenses' => [
                'view expenses',
                'create expenses',
                'edit expenses',
                'delete expenses',
            ],
            'Coupons & Offers' => [
                'view coupons',
                'create coupons',
                'edit coupons',
                'delete coupons',
            ],
            'Delivery Management' => [
                'view delivery',
                'manage delivery',
                'assign delivery',
            ],
            'Billing & Payments' => [
                'view payments',
                'process payments',
                'refund payments',
            ],
            'Reports & Analytics' => [
                'view reports',
                'export reports',
            ],
            'User Management' => [
                'view users',
                'create users',
                'edit users',
                'delete users',
            ],
            'Roles & Permissions' => [
                'view roles',
                'create roles',
                'edit roles',
                'delete roles',
            ],
        ];
    }

    public function index()
    {
        $roles = Role::withCount(['permissions', 'users'])->get();
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        $permissionGroups = static::getPermissionGroups();
        $allPermissions = Permission::pluck('name')->toArray();
        return view('roles.create', compact('permissionGroups', 'allPermissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:roles,name',
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $roleName = strtolower(str_replace(' ', '_', trim($request->name)));

        $role = Role::create([
            'name' => $roleName,
            'guard_name' => 'web',
        ]);

        $role->syncPermissions($request->permissions);

        return redirect()->route('roles.index')->with('success', 'Role "' . $role->name . '" created successfully.');
    }

    public function edit(Role $role)
    {
        $permissionGroups = static::getPermissionGroups();
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        return view('roles.edit', compact('role', 'permissionGroups', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('roles', 'name')->ignore($role->id)],
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'exists:permissions,name',
        ]);

        if (in_array($role->name, ['super_admin', 'owner']) && $request->name !== $role->name) {
            return back()->with('error', 'The system role name cannot be renamed.');
        }

        $roleName = strtolower(str_replace(' ', '_', trim($request->name)));

        $role->update([
            'name' => $roleName,
        ]);

        $role->syncPermissions($request->permissions);

        return redirect()->route('roles.index')->with('success', 'Role "' . $role->name . '" updated successfully.');
    }

    public function destroy(Role $role)
    {
        if (in_array($role->name, ['super_admin', 'owner'])) {
            return back()->with('error', 'Core system role "' . $role->name . '" cannot be deleted.');
        }

        if ($role->users()->count() > 0) {
            return back()->with('error', 'Cannot delete role "' . $role->name . '" as it is currently assigned to ' . $role->users()->count() . ' user(s).');
        }

        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }
}
