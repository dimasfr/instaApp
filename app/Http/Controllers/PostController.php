<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Like;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    // List semua post aktif
    public function index()
    {
        $posts = Post::with([
            'photos',
            'user',         // info user yang posting
            'likes.user',   // info user yang like
            'comments.user' // info user yang comment
        ])->latest()->paginate(10);

        // Format respons: tambahkan likes_count dan comments
        $posts->getCollection()->transform(function ($post) {
            return [
                'id' => $post->id,
                'content' => $post->content,
                'user' => $post->user,
                'photos' => $post->photos,
                'likes_count' => $post->likes->count(),
                'likes' => $post->likes->map(function($like){
                    return [
                        'id' => $like->id,
                        'user_id' => $like->user_id,
                        'user_name' => $like->user->name,
                    ];
                }),
                'comments_count' => $post->comments->count(),
                'comments' => $post->comments->map(function($comment){
                    return [
                        'id' => $comment->id,
                        'user_id' => $comment->user_id,
                        'user_name' => $comment->user->name,
                        'content' => $comment->content,
                        'created_at' => $comment->created_at,
                    ];
                }),
                'created_at' => $post->created_at,
                'updated_at' => $post->updated_at,
            ];
        });

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

    public function getLikes(Post $post)
    {
        $likes = $post->likes()->with('user')->latest()->get();
        return response()->json($likes);
    }

    public function toggleLike(Post $post)
    {
        $user = Auth::user();
        $like = $post->likes()->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
            return response()->json(['liked' => false, 'likes_count' => $post->likes()->count()]);
        } else {
            $post->likes()->create(['user_id' => $user->id]);
            return response()->json(['liked' => true, 'likes_count' => $post->likes()->count()]);
        }
    }

    public function comment(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string|max:500',
        ]);

        $comment = $post->comments()->create([
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        $comment->load('user');

        return response()->json($comment, 201);
    }

    // Get comments
    public function getComments(Post $post)
    {
        $comments = $post->comments()->with('user')->latest()->get();
        return response()->json($comments);
    }

}
