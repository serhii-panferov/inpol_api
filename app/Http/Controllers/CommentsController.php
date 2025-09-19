<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    public function store(CommentRequest $request, Post $post)
    {
        $post->comments()->create($request->validated());
        return redirect()->route('posts.show', compact('post'))->with('success', 'Comment added successfully!');
    }
}
