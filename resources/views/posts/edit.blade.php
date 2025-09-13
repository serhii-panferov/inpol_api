@extends('layouts.master')
@section('title', 'Edit Post')
@section('h1', 'Edit Post')
@section('content')
    <form method="POST" action="{{ route('posts.update', $post) }}">
        @csrf
        @method('PUT')

        <label>Title:</label><br>
        <input type="text" name="title" value="{{ old('title', $post->title) }}"><br><br>

        <label>Content:</label><br>
        <textarea name="content">{{ old('content', $post->content) }}</textarea><br><br>

        <button type="submit">Update</button>
    </form>
@endsection
