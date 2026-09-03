<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // User permissions
            'create_user', 'edit_user', 'delete_user', 'view_user',
            // Page permissions
            'create_page', 'edit_page', 'delete_page', 'view_page',
            // Category permissions
            'create_category', 'edit_category', 'delete_category', 'view_category',
            // Slide permissions
            'create_slide', 'edit_slide', 'delete_slide', 'view_slide',
            // Product permissions
            'create_product', 'edit_product', 'delete_product', 'view_product',
            // Customer permissions
            'create_customer', 'edit_customer', 'delete_customer', 'view_customer',
            // Order permissions
            'create_order', 'edit_order', 'delete_order', 'view_order',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create or get admin role and assign all permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions($permissions);

        // Create or get manager role with limited permissions
        // $managerRole = Role::firstOrCreate(['name' => 'manager']);
        // $managerRole->syncPermissions([
        //     'view products',
        //     'manage products',
        //     'view categories',
        //     'manage categories',
        //     'view orders',
        //     'manage orders',
        // ]);
    }
}
