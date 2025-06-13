<?php

namespace App\Http\Controllers;

use App\Models\superAdminModel;
use Illuminate\Http\Request;

class SuperAdminApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = superAdminModel::all();
        return $data;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = superAdminModel::where('key', '=', $id)->first();
        
        if ($data) {
            return [
                'id' => $data->id,
                'email' => $data->email,
                'password' => $data->password,
                'key' => $data->key,
            ];
        }
        return null;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
