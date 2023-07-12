<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            TagSeeder::class,
            StyleSeeder::class,
            UserSeeder::class,
            ArtSeeder::class,
            LikeSeeder::class,
            CommentSeeder::class,
        ]);
    }
}
