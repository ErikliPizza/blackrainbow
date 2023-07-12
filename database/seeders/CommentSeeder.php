<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Art;
use App\Models\Comment;
use App\Models\User;
class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $artRecords = Art::inRandomOrder()->limit(50)->get();

        $users->each(function ($user) use ($artRecords) {
            $artRecords->each(function ($artRecord) use ($user) {
                Comment::factory()->create([
                    'user_id' => $user->id,
                    'art_id' => $artRecord->id,
                ]);
            });
        });
    }
}
