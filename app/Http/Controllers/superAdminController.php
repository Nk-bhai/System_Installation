<?php

namespace App\Http\Controllers;

use App\Models\superAdminModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SuperAdminController extends Controller
{
    public function index()
    {
        $data = superAdminModel::all();
        return view('SuperAdmin', ['data' => $data]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'unique:superadmin,email', 'email', 'min:2'],
            'password' => ['required', 'regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[^A-Za-z0-9]).{8}$/'],
            'key' => ['required', 'max:14'],
        ], [
            'password.regex' => 'Password must contain at least 1 uppercase, 1 lowercase, 1 digit, 1 special character, and be exactly 8 characters.',
        ]);

        superAdminModel::create([
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'key' => $request->input('key'),
        ]);

        return redirect()->route('superAdmin.index');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $data = superAdminModel::where('id', $id)->get();
        return view('SuperAdminEdit', ['data' => $data]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'email' => ['required', 'email', 'min:2'],
            'key' => ['required', 'max:14'],
        ]);

        $updateData = [
            'email' => $request->input('email'),
            'key' => $request->input('key'),
        ];

        if ($request->has('password') && $request->input('password')) {
            $request->validate([
                'password' => ['regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[^A-Za-z0-9]).{8}$/'],
            ], [
                'password.regex' => 'Password must contain at least 1 uppercase, 1 lowercase, 1 digit, 1 special character, and be exactly 8 characters.',
            ]);
            $updateData['password'] = Hash::make($request->input('password'));
        }

        superAdminModel::where('id', $id)->update($updateData);
        return redirect()->route('superAdmin.index');
    }

    public function destroy(string $id)
    {
        superAdminModel::where('id', $id)->delete();
        return redirect()->route('superAdmin.index');
    }
}