<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 10);
        $posts = Post::orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json($posts);
    }

    public function store(StorePostRequest $request): JsonResponse
    {
        $post = Post::create($request->validated());

        return response()->json([
            'message' => 'Post created successfully.',
            'data' => $post,
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $post = Post::findOrFail($id);

        return response()->json(['data' => $post]);
    }

    public function update(UpdatePostRequest $request, string $id): JsonResponse
    {
        $post = Post::findOrFail($id);
        $post->update($request->validated());

        return response()->json([
            'message' => 'Post updated successfully.',
            'data' => $post,
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return response()->json([
            'message' => 'Post deleted successfully.',
        ]);
    }
}
