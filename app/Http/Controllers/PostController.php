<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class PostController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(Post::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Posts/Index', [
            'posts' => PostResource::collection(Post::with('user')->latest()->latest('id')->paginate())
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Posts/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|min:10|max:120|string',
            'body'  => 'required|min:100|max:10000|string'
        ]);

        $post = Post::create([
            ...$data,
            'user_id' => $request->user()->id
        ]);

        return redirect($post->showRoute());
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Post $post)
    {
        if (!Str::contains($post->showRoute(), $request->path())) {
            return redirect($post->showRoute($request->query()), status: 301);
        }

        $post->load('user');

        return Inertia::render('Posts/Show', [
            'post'     => fn() => PostResource::make($post),
            'comments' => fn() => CommentResource::collection($post->comments()
                ->with('user')
                ->latest()
                ->latest('id')
                ->paginate(5)
            )
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}
