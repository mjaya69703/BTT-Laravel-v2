<?php

namespace Database\Seeders;

use App\Models\User;
use Hash;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $user = [
        [
            'name' => 'Admin User',
            'role' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin1234')
        ],
        [
            'name' => 'Member User',
            'role' => 'user',
            'email' => 'user@example.com',
            'password' => Hash::make('user1234')
        ]];

        foreach ($user as $key => $makeuser) {
            \App\Models\User::create($makeuser);
        }

        $movies = [
            [
                'title' => 'Imagination 2021',
                'image' => 'default.jpg',
                'description' => 'Film tentang ini itu',
                'release_date' => 20-01-2023,
            ],
            [
                'title' => 'Imagination 2023',
                'image' => 'default.jpg',
                'description' => 'Film tentang ini itu',
                'release_date' => 20-01-2024,
            ],
            [
                'title' => 'Imagination 2024',
                'image' => 'default.jpg',
                'description' => 'Film tentang ini itu',
                'release_date' => 20-01-2025,
            ],
        ];

        foreach ($movies as $key => $makemovies) {
            \App\Models\Movies::create($makemovies);
        }

        $reviews = [
            [
                'user_id' => 2,
                'movie_id' => 1,
                'rating' => 5,
                'review' => 'Bagus banget filmnya',
            ],
            [
                'user_id' => 2,
                'movie_id' => 2,
                'rating' => 3,
                'review' => 'Mayan banget filmnya',
            ],
            [
                'user_id' => 2,
                'movie_id' => 3,
                'rating' => 5,
                'review' => 'OKELAH banget filmnya',
            ],
        ];

        foreach ($reviews as $key => $makereviews) {
            \App\Models\Review::create($makereviews);
        }
    }
}
