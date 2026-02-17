<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // 🔹 Use existing images from storage/app/public/posts
        $images = Storage::disk('public')->files('posts');

        return [
            'post_title' => Str::limit(fake()->sentence(), 50, ''),
            'post_description' => fake()->paragraph(50, true),
            'post_status' => 1,
            'author_id' => optional(User::inRandomOrder()->first())->id,            
            // 🔹 Pick a random existing image (no download, no copy)
            'image' => !empty($images)
                ? fake()->randomElement($images)
                : null,
        ];
    }
}
