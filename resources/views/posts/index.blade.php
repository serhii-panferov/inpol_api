@extends('layouts.master')
@section('title', 'Posts')
@section('content')
    <h1>All Posts</h1>
    <a href="{{ route('posts.create') }}">Create New Post</a>
    <ul>
        @forelse($posts as $post)
            <li>
                <strong>{{ $post->title }}</strong><br>
                {{ $post->content }}
                <a href="{{ route('posts.edit', $post) }}">Edit</a>
                <a href="{{ route('posts.show', $post) }}">Show</a>
                <form action="{{ route('posts.destroy', $post) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" >Delete</button>
                </form>
            </li>
        @empty
            <li>No posts yet.</li>
        @endforelse
    </ul>
@endsection
