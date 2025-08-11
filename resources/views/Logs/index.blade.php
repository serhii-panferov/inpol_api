<div>
@extends('layouts/master')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1>Logs</h1>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Method</th>
                        <th>Status Code</th>
                        <th>Url</th>
                        <th>Request Body</th>
                        <th>Created_at</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                        <tr>
                            <td>{{ $log->id }}</td>
                            <td>{{ $log->method }}</td>
                            <td>{{ $log->status_code }}</td>
                            <td>{{ $log->url }}</td>
                            <td>{{ json_encode($log->request_body) }}</td>
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
