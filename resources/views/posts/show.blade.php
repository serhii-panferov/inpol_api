@extends('layouts.master')
@section('title', $post->title)
@section('h1', $post->title)
@section('content')
    <p>{{ $post->content }}</p>
    <h3>Comments</h3>
    <form method="POST" action="{{ route('comments.store', $post) }}">
        @csrf
        <label>Your Name:</label><br>
        <input type="text" name="author"><br><br>

        <label>Your Comment:</label><br>
        <textarea name="content"></textarea><br><br>

        <button type="submit">Add Comment</button>

    </form>
    <ul>
        @forelse($post->comments as $comment)
            <li><strong>{{ $comment->author }}:</strong> {{ $comment->content }}</li>
        @empty
            <li>No comments yet.</li>
        @endforelse
    </ul>
@endsection
