<?php

namespace App\Http\Controllers;

use App\Models\RequestLogs;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class InpolLogs extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Paginator::useBootstrap();
        $logs = RequestLogs::orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(50);
        return view('logs.index', [
            'logs' => $logs,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(RequestLogs $requestLogs, $id)
    {
        $logs = $requestLogs::where('id', $id)
            ->first();
        return view('logs.show', [
            'logs' => $logs,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RequestLogs $requestLogs)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RequestLogs $requestLogs)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RequestLogs $requestLogs)
    {
        //
    }

}
