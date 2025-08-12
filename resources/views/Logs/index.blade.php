<div>
@extends('layouts/master')
@section('content')
<div class="container-fluid logs">
    <div class="row">
        <div class="col-12">
            <h1>Logs</h1>
            <table class="table table-striped" style="font-size: 13px;">
                <thead>
                    <tr>
{{--                        <th>ID</th>--}}
{{--                        <th>Method</th>--}}
{{--                        <th>Status Code</th>--}}
                        <th>Url</th>
                        <th>Request Headers</th>
                        <th>Request Body</th>
                        <th>Response Headers</th>
                        <th>Response Headers</th>
                        <th>Created_at</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                        <tr>
{{--                            <td>{{ $log->id }}</td>--}}
{{--                            <td>{{ $log->method }}</td>--}}
{{--                            <td>{{ $log->status_code }}</td>--}}
                            <td>{{ Str::after($log->url, \App\Services\Inpol\InpolClient::INPOL_API_DOMAIN)}}</td>
                            <td>{{ Str::limit(json_encode($log->request_headers), 50) }}</td>
                            <td>{{ Str::limit(json_encode($log->request_body), 50) }}</td>
                            <td>{{ Str::limit(json_encode($log->response_headers), 50) }}</td>
                            <td>{{ Str::limit(json_encode($log->response_body), 50) }}</td>
                            <td>{{ $log->created_at }}</td>
                            <td>
                                <form action="{{ route( 'logs.destroy', $log->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                                <form action="{{ route( 'logs.show', $log->id) }}" method="GET">
                                    <button type="submit" class="btn btn-info">Show</button>
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
