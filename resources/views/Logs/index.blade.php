<div>
@extends('layouts/master')
@section('content')
<div class="container-fluid logs mx-auto p-4">
    <div class="row">
        <div class="col-12">
            <h1 class="text-xl font-bold mb-4">Request Logs</h1>
            {{$logs->links()}}
            <table class="table table-striped w-full" style="font-size: 13px;">
                <thead>
                    <tr>
{{--                        <th>ID</th>--}}
{{--                        <th>Method</th>--}}
{{--                        <th>Status Code</th>--}}
                        <th>Url</th>
                        <th>Status</th>
                        <th>Request Body</th>
                        <th>Response Headers</th>
                        <th>Response Body</th>
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
                            <td>{{ $log->status_code }}</td>
                            <td>
{{--                                {{ Str::limit(json_encode($log->request_body), 50) }}--}}
                                <button onclick="toggleDetails('headers-{{ $log->id }}')" class="text-blue-600 underline">Show</button>
                                <div id="headers-{{ $log->id }}" class="hidden mt-1 bg-gray-100 p-2 text-xs rounded">
                                    <pre>{{ json_encode($log->request_headers, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                </div>
                            </td>
                            <td>{{ Str::limit(json_encode($log->response_headers), 50) }}</td>
                            <td>
                                {{ Str::limit(json_encode($log->response_body), 50) }}
                                <button onclick="toggleDetails('resp-{{ $log->id }}')" class="text-blue-600 underline">Show</button>
                                <div id="resp-{{ $log->id }}" class="hidden mt-1 bg-gray-100 p-2 text-xs rounded max-h-64 overflow-y-auto">
                                    <pre>{{ Str::limit($log->response_body, 200) }}</pre>
                                </div>
                            </td>
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
            {{$logs->links()}}
        </div>
    </div>
    <script>
        function toggleDetails(id) {
            const el = document.getElementById(id);
            el.classList.toggle('hidden');
        }
    </script>
    <!-- The only way to do great work is to love what you do. - Steve Jobs -->
</div>
@endsection
