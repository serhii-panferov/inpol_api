@extends('layouts.master')
@section('title', 'Create Post')
@section('h1', 'Create a New Post')
@section('content')
    <form method="POST" action="{{ route('posts.store') }}">
        @csrf
        <label>Title:</label><br>
        <input type="text" name="title"><br><br>

        <label>Content:</label><br>
        <textarea name="content"></textarea><br><br>

        <button type="submit">Save</button>
    </form>
@endsection
