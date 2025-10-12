<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    // List semua post aktif
    public function index()
    {
        $posts = Post::with('photos')->latest()->paginate(10);
        return response()->json($posts);
    }

    // List semua post termasuk soft deleted
    public function all()
    {
        $posts = Post::with('photos')->withTrashed()->latest()->paginate(10);
        return response()->json($posts);
    }

    // Detail post
    public function show(Post $post)
    {
        $post->load('photos');
        return response()->json($post);
    }

    // Buat post baru
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'nullable|string',
            'photos.*' => 'image|max:2048', // max 2MB per foto
        ]);

        $post = Post::create([
            'user_id' => Auth::user()->id, // sesuaikan dengan auth user
            'content' => $request->content,
        ]);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('posts', 'public');
                $post->photos()->create(['photo_path' => $path]);
            }
        }

        return response()->json($post->load('photos'), 201);
    }

    // Update post
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'nullable|string',
            'photos.*' => 'image|max:2048',
        ]);

        // Update content hanya jika dikirim
        if ($request->has('content')) {
            $post->content = $request->content;
            $post->save();
        }

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('posts', 'public');
                $post->photos()->create(['photo_path' => $path]);
            }
        }

        return response()->json($post->load('photos'));
    }

    // Soft delete post
    public function destroy(Post $post)
    {
        $post->delete();
        return response()->json(['message' => 'Post soft deleted']);
    }

    // Restore soft deleted post
    public function restore($id)
    {
        $post = Post::withTrashed()->findOrFail($id);
        $post->restore();
        return response()->json(['message' => 'Post restored', 'post' => $post->load('photos')]);
    }

    // Hard delete post
    public function forceDelete($id)
    {
        $post = Post::withTrashed()->findOrFail($id);

        // Hapus file foto dari storage
        foreach ($post->photos as $photo) {
            Storage::disk('public')->delete($photo->photo_path);
        }

        $post->forceDelete();
        return response()->json(['message' => 'Post permanently deleted']);
    }
}
