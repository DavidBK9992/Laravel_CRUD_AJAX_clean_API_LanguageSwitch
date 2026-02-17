<?php

namespace App\Observers;

use App\Models\Post;
use Illuminate\Support\Facades\Cache;

class PostObserver
{
    public function updated(Post $post): void
    {
        Cache::forget("post_{$post->id}");
        Cache::forget('posts_list');
    }

    public function deleted(Post $post): void
    {
        Cache::forget("post_{$post->id}");
        Cache::forget('posts_list');
    }
}
