<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $validate = $request->validate([
            'author' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $post->comments()->create($validate);
        return redirect()->route('posts.show', compact('post'))->with('success', 'Comment added successfully!');
    }
}
