<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\StorePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = request()->user();
        $posts = $user->posts()->paginate();
        return PostResource::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $data = $request->validated();
 
        $data['author_id'] = $request->user()->id;

        $post = Post::create($data);

        return new PostResource($post);
        // ->setStatusCode(201); 
        ;
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        abort_if(Auth::id() != $post->author_id, 403, 'Access Forbidden!');
        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        abort_if(Auth::id() != $post->author_id, 403, 'Access Forbidden!');
        // $data = $request->validate([
            
        // ]);

        // $post->update($data);

        // return new PostResource($post);

        $data = $request->only(['post_title','post_description']);
        $data['post_status'] = $request->post_status === 'active';

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($post->image);
            $data['image'] = $request->file('image')->store('posts', 'public');
        }

        $post->update($data);

        //    return redirect()->route('posts.index')
        //              ->with('success', 'Post updated successfully!');
        return new PostResource($post);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        abort_if(Auth::id() != $post->author_id, 403, 'Access Forbidden!');
        $post->delete();
        return response()->noContent();
    }
}
