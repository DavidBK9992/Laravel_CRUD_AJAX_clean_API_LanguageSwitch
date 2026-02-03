<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Order Posts by latest to oldest update and id highest to lowest
        $posts = Post::orderByDesc('updatet_at')->orderByDesc('id')->paginate(10);

        return view('posts.index', [
            'posts' => $posts,
        ]);
    }

    /**
     * Show the form for creating a new post.
     */
    public function create()
    {
        return view('posts.add');
    }

    /**
     * Display the clicked post.
     */
    public function show(Post $post)
    {
        // Logic to show a specific post by ID
        return view('posts.show', [
            'post' => $post
        ]);
    }

    /**
     * Store a newly created post in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'post_title' => 'required|string|unique:posts,post_title',
            'post_description' => 'required|string|unique:posts,post_description',
            'post_status' => 'required|in:active,inactive',
            'image' => 'required|image|mimes:jpg,jpeg,png,gif',
        ]);

        $data = $request->only(['post_title', 'post_description', 'post_status']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('posts', 'public');
        }

        Post::create($data);

        return redirect()->route('posts.index')
            ->with('success', 'Post created successfully.');
    }


    /**
     * Show the form for editing an existing post.
     */

    public function edit(Post $post)
    {
        return view('posts.edit', [
            'post' => $post,
        ]);
    }


    /**
     * Show the form for updating the specified post.
     */

    public function update(Request $request, Post $post)
    {
        $request->validate([
            'post_title' => 'required|string|unique:posts,post_title,' . $post->id,
            'post_description' => 'required|string|unique:posts,post_description,' . $post->id,
            'post_status' => 'required|in:active,inactive',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif',
        ]);

        $data = $request->only(['post_title', 'post_description', 'post_status']);

        /**
         * Handle image upload if a new image is provided
         */

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('posts', 'public');
        }

        $data['updated_at'] = now();

        $post->update($data);

        return redirect()->route('posts.index')
            ->with('success', 'Post updated successfully.');
    }


    public function destroy(Post $post)
    {
        // Delete image (if available)
        if ($post->image && Storage::disk('public')->exists($post->image)) {
            Storage::disk('public')->delete($post->image);
        }

        // Delete Post ()
        if ($post->delete()) {
            return redirect()->route('posts.index')->with('success', 'Post deleted successfully.');
        }

        return redirect()->route('posts.index')->with('error', 'Failed to delete the post.');
    }
}
