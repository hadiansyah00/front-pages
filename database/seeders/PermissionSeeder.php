<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // User permissions
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',

            // Role permissions
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',

            // Proyek permissions
            'proyek-list',
            'proyek-create',
            'proyek-edit',
            'proyek-delete',

            // Kategori permissions
            'kategori-list',
            'kategori-create',
            'kategori-edit',
            'kategori-delete',

            // Layanan permissions
            'layanan-list',
            'layanan-create',
            'layanan-edit',
            'layanan-delete',

            // Testimoni permissions
            'testimoni-list',
            'testimoni-create',
            'testimoni-edit',
            'testimoni-delete',

            // Kontak permissions
            'kontak-list',
            'kontak-create',
            'kontak-edit',
            'kontak-delete',

            // Artikel permissions
            'artikel-list',
            'artikel-create',
            'artikel-edit',
            'artikel-delete',

            // Setting permissions
            'setting-list',
            'setting-create',
            'setting-edit',
            'setting-delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}