<div>
@extends('layouts/master')
@section('content')
<div class="container-fluid logs">
    <div class="row">
        <div class="col-12">
            <h1>Logs</h1>
            <table class="table-responsive table-striped" style="font-size: 13px;">
                <thead>
                    <tr>
                        <th>Column</th>
                        <th>Value</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($logs->getAttributes() as $key => $value)
                        <tr>
                            <td><b>{{ $key }}</b></td>
                            <td>{{ $value }}</td>
                        <tr>
                @endForeach
                </tbody>
            </table>
        </div>
    </div>
    <!-- The only way to do great work is to love what you do. - Steve Jobs -->
</div>
@endsection
