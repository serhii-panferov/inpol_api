<div>
{{--@extends('layouts.app');--}}
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Logs</h1>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Level</th>
                        <th>Message</th>
                        <th>Context</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                        <tr>
                            <td>{{ $log->id }}</td>
                            <td>{{ $log->level }}</td>
                            <td>{{ $log->message }}</td>
                            <td>{{ json_encode($log->context) }}</td>
                            <td>{{ $log->created_at }}</td>
                            <td>
                                <form action="{{ route( 'logs.destroy', $log->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!-- The only way to do great work is to love what you do. - Steve Jobs -->
</div>
@endsection
