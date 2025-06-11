<?php

namespace App\Http\Controllers;

use App\Models\superAdminModel;
use DB;
use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // dd(DB::connection()->getDatabaseName());
        $data = superAdminModel::all();
        return view('SuperAdmin', ['data' => $data]);

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
        $request->validate([
            'email' => ['required', 'unique:superAdmin', 'email', 'min:2'],
            'password' => ['required', 'max:8'],
            'key' => ['required', 'max:14'],
        ]);
        superAdminModel::insert([
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'key' => $request->input('key')
        ]);

        return redirect()->route('superAdmin.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = superAdminModel::where('id', '=', $id)->get();
        return view('SuperAdminEdit', ['data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'email' => ['required', 'email', 'min:2'],
            // 'password' => ['required', 'max:8'],
            'key' => ['required', 'max:14']
        ]);
        superAdminModel::where('id', '=', $id)->update([
            'email' => $request->input('email'),
            'key' => $request->input('key')
        ]);
        return redirect()->route('superAdmin.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        superAdminModel::where('id', '=', $id)->delete();
        return redirect()->route('superAdmin.index');
    }
}
