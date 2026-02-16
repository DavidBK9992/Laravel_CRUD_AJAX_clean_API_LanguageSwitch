<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Requests\UpdatePostStatusRequest;
use App\Http\Requests\DeletePostRequest;

class PostApiController extends Controller
{
    /**
     * GET /api/posts
     */
    public function index(): JsonResponse
    {
        $posts = Post::latest()->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'Hello there! Posts fetched successfully.',
            'data' => $posts,
        ]);
    }

    /**
     * POST /api/posts
     */
    public function store(StorePostRequest $request): JsonResponse
    {
        $data = $request->only(['post_title', 'post_description']);
        $data['post_status'] = $request->boolean('post_status');
        $data['image'] = $request->file('image')->store('posts', 'public');

        $post = Post::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Post created successfully.',
            'data' => $post,
        ], 201);
    }

    /**
     * GET /api/posts/{post}
     */
    public function show(Post $post): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Post fetched successfully.',
            'data' => $post,
        ]);
    }

    /**
     * PUT/PATCH /api/posts/{post}
     */
    public function update(UpdatePostRequest $request, Post $post): JsonResponse
    {
        $data = $request->only(['post_title', 'post_description']);
        $data['post_status'] = $request->boolean('post_status');

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($post->image);
            $data['image'] = $request->file('image')->store('posts', 'public');
        }

        $post->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Post updated successfully.',
            'data' => $post->fresh(),
        ]);
    }

    /**
     * DELETE /api/posts/{post}
     */
    public function destroy(DeletePostRequest $request, Post $post): JsonResponse
    {
       
        if ((int) $request->input('id') !== (int) $post->id) {
            return response()->json([
                'success' => false,
                'message' => 'Payload id does not match route id.',
            ], 422);
        }

        Storage::disk('public')->delete($post->image);
        $post->delete();

        return response()->json([
            'success' => true,
            'message' => 'Post deleted successfully.',
        ]);
    }

    /**
     * PATCH /api/posts/{post}/status
     */
    public function updateStatus(UpdatePostStatusRequest $request, Post $post): JsonResponse
    {
  
        if ((int) $request->input('id') !== (int) $post->id) {
            return response()->json([
                'success' => false,
                'message' => 'Payload id does not match route id.',
            ], 422);
        }

        $post->update([
            'post_status' => (bool) $request->boolean('status'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully.',
            'data' => $post->fresh(),
        ]);
    }
}
