<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::orderByDesc('created_at')->orderByDesc('id')->paginate(10);

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
            'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $data = $request->only(['post_title', 'post_description', 'post_status']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('posts', 'public');
        }

        Post::create($data);

        return redirect()->route('posts.index')
            ->with('success', 'Post created successfully.');
    }
    public function edit(Post $post)
    {
        return view('posts.edit', [
            'post' => $post,
        ]);
    }

    public function update(Request $request, Post $post)
    {
        $request->validate([
            'post_title' => 'required|string|unique:posts,post_title,' . $post->id,
            'post_description' => 'required|string|unique:posts,post_description,' . $post->id,
            'post_status' => 'required|in:active,inactive',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $data = $request->only(['post_title', 'post_description', 'post_status']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('posts', 'public');
        }

        $post->update($data);

        return redirect()->route('posts.index')
            ->with('success', 'Post updated successfully.');
    }
    public function destroy(Post $post)
    {
        return $post->delete()
            ? redirect()->route('posts.index')->with('success', 'Post deleted successfully.')
            : redirect()->route('posts.index')->with('error', 'Failed to delete the post.');
    }
}
