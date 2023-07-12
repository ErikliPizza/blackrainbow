<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Art;
use App\Models\User;
class LikeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $artRecords = Art::inRandomOrder()->limit(50)->get();

        $users->each(function ($user) use ($artRecords) {
            $randomArtRecords = $artRecords->random(50);

            $randomArtRecords->each(function ($artRecord) use ($user) {
                $user->likedArts()->attach($artRecord);
            });
        });
    }
}
