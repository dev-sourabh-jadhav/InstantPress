<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PermissionsModel;

class PermissionsSeeder extends Seeder
{
    public function run()
    {
        // Main menu items
        $menuPermissions = [
            'WP Material Menu',
            'Plugin View',
            'Themes View',
            'Version View',
            'ADD Plugin Categories View',
            'Setting Menu',
            'SMTP View',
            'Manage Role View',
            'Site Setting View',
            'Manage Users View',
            'Manage Sites View',
            'PAYMENT Settings Menu',
            'Payment Configuration View',
            'Payment History View',
            'View Subscription View',
            'Add Plan View',
            'ALL SITES Menu',
        ];

        foreach ($menuPermissions as $permission) {
            PermissionsModel::create([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }
    }
}
