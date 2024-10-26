<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ManageUsersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('manage_users_table')->insert([
            [
                'user_id' => 2,
                'phone' => '9876543210',
                'country' => 'USA',
                'state' => 'California',
                'city' => 'Los Angeles',
                'pincode' => '90001',
                'gender' => 'Male',
                'dob' => '1990-01-01',
                'subscription_type' => 'Premium',
                'start_date' => '2023-01-01',
                'end_date' => '2024-01-01',
                'starter' => 'yes',
                
            ],
           
        ]);
    }
}
