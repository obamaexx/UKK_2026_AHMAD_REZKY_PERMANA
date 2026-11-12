<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Tool;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 3 akun, satu untuk tiap level pengguna
        User::create([
            'name'     => 'Admin',
            'username' => 'admin',
            'email'    => 'admin@mail.com',
            'password' => 'password',
            'role'     => 'admin',
        ]);

        User::create([
            'name'     => 'Petugas',
            'username' => 'petugas',
            'email'    => 'petugas@mail.com',
            'password' => 'password',
            'role'     => 'petugas',
        ]);

        User::create([
            'name'     => 'Peminjam',
            'username' => 'peminjam',
            'email'    => 'peminjam@mail.com',
            'password' => 'password',
            'role'     => 'peminjam',
        ]);

        // Data contoh supaya bisa langsung diuji
        $kategori = Category::create([
            'name'        => 'Elektronik',
            'description' => 'Alat-alat elektronik',
        ]);

        Tool::create([
            'category_id' => $kategori->id,
            'code'        => 'ALT-001',
            'name'        => 'Proyektor',
            'stock'       => 5,
            'condition'   => 'baik',
        ]);

        Tool::create([
            'category_id' => $kategori->id,
            'code'        => 'ALT-002',
            'name'        => 'Laptop',
            'stock'       => 10,
            'condition'   => 'baik',
        ]);
    }
}