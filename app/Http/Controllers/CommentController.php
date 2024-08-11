<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Routing\Controller; // Bunu useAuthorizesRequest için ekledik
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
// use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(Comment::class);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Post $post)
    {
        $data = $request->validate([
            'body' => 'required|string|max:2500'
        ]);

        Comment::create([
            ...$data,
            'post_id' => $post->id,
            'user_id' => $request->user()->id,
        ]);

        return redirect($post->showRoute())
            ->banner('Comment added successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        // Gate::authorize('update', $comment);

        $data = $request->validate(['body' => ['required', 'string', 'max:2500']]);

        $comment->update($data);

        return redirect($comment->post->showRoute(['page' => $request->query('page')]))
            ->banner('Comment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Comment $comment)
    {
        // Gate::authorize('delete', $comment);

        $comment->delete();

        return redirect($comment->post->showRoute(['page' => $request->query('page')]))
            ->banner('Comment deleted successfully.');

        // return redirect()->back();
    }
}
