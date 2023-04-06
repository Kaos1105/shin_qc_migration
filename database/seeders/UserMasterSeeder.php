<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'user_code' => 1234,
            'name' => "admin",
            'role_indicator' => 1,
            'position' => 1,
            'email' => "admin@qc-system.com",
            'phone' => "0123456789",
            'login_id' => "admin",
            'password' => Hash::make('admin'),
            'password_encrypt' => 'MTIzNDU=',
            'statistic_classification' =>2,
            'access_authority' => 2,
            'display_order' => 1,
            // 'summary_classification' => 1,
            'use_classification' => 1,
            'created_by' => 0,
            'updated_by' => 0
        ]);
        DB::table('users')->insert([
            'user_code' => 1235,
            'name' => "user",
            'role_indicator' => 1,
            'position' => 1,
            'email' => "user@qc-system.com",
            'phone' => "0123456789",
            'login_id' => "user",
            'password' => Hash::make('user'),
            'password_encrypt' => 'MTIzNDU=',
            'statistic_classification' =>2,
            'access_authority' => 1,
            'display_order' => 1,
            // 'summary_classification' => 1,
            'use_classification' => 1,
            'created_by' => 0,
            'updated_by' => 0
        ]);
    }
}
