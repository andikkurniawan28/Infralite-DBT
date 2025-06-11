<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use App\Models\DatabaseType;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::insert([
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('admin888'),
                'is_active' => 1,
            ]
        ]);

        DatabaseType::insert([
            [
                'icon' => '/icons/sqlite.svg',
                'driver' => 'sqlite',
                'brand' => 'SQLite',
                'default_port' => null,
                'default_charset' => null,
                'default_collation' => null,
                'default_schema' => null,
            ],
            [
                'icon' => '/icons/mysql.svg',
                'driver' => 'mysql',
                'brand' => 'MySQL',
                'default_port' => '3306',
                'default_charset' => 'utf8mb4',
                'default_collation' => 'utf8mb4_unicode_ci',
                'default_schema' => null,
            ],
            [
                'icon' => '/icons/postgresql.svg',
                'driver' => 'pgsql',
                'brand' => 'PostgreSQL',
                'default_port' => '5432',
                'default_charset' => 'UTF8',
                'default_collation' => null,
                'default_schema' => 'public',
            ],
            [
                'icon' => '/icons/mariadb.svg',
                'driver' => 'mysql',
                'brand' => 'MariaDB',
                'default_port' => '3306',
                'default_charset' => 'utf8mb4',
                'default_collation' => 'utf8mb4_general_ci',
                'default_schema' => null,
            ],
        ]);
    }
}
