<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $superAdminRole = Role::firstOrCreate([
            'name' => 'super_admin',
            'guard_name' => 'web',
        ]);

        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $mahasiswaRole = Role::firstOrCreate([
            'name' => 'mahasiswa',
            'guard_name' => 'web',
        ]);

        $admin = User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin',
                'nomor_hp' => '081111111111',
                'password' => Hash::make('password'),
                'role' => User::ROLE_ADMIN,
            ]
        );

        $admin->syncRoles([$superAdminRole, $adminRole]);

        $mahasiswaSatu = User::updateOrCreate(
            ['email' => 'mahasiswa@demo.com'],
            [
                'name' => 'Raka Pratama',
                'nomor_hp' => '082222222222',
                'password' => Hash::make('password'),
                'role' => User::ROLE_MAHASISWA,
            ]
        );

        $mahasiswaSatu->syncRoles([$mahasiswaRole]);

        $mahasiswaDua = User::updateOrCreate(
            ['email' => 'sinta@demo.com'],
            [
                'name' => 'Sinta Ayu',
                'nomor_hp' => '083333333333',
                'password' => Hash::make('password'),
                'role' => User::ROLE_MAHASISWA,
            ]
        );

        $mahasiswaDua->syncRoles([$mahasiswaRole]);
    }
}