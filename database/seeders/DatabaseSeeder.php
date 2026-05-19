<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Post::create([
            'title' => 'Welcome to Laravel + MongoDB',
            'content' => 'This is a demo post created by the database seeder. Laravel with MongoDB provides a flexible and powerful backend solution.',
            'author' => 'Admin',
        ]);

        Post::create([
            'title' => 'Getting Started with React',
            'content' => 'React is a JavaScript library for building user interfaces. Combined with Laravel API, it creates a powerful full-stack application.',
            'author' => 'Admin',
        ]);
    }
}
