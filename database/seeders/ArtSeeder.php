<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Art;
use App\Models\User;
use App\Models\Style;
use App\Models\Tag;
class ArtSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::all()->each(function ($user) {
            $arts = Art::factory(10)->make()->each(function ($art) use ($user) {
                $styleIds = Style::pluck('id')->random(3); // Get 3 random style IDs
                $tagIds = Tag::pluck('id')->random(6); // Get 6 random tag IDs

                $art->user()->associate($user); // Associate the art with the user
                $art->save(); // Save the art to generate an ID

                $art->styles()->attach($styleIds); // Attach the style IDs
                $art->tags()->attach($tagIds); // Attach the tag IDs
            });

            $user->arts()->saveMany($arts);
        });
    }
}
