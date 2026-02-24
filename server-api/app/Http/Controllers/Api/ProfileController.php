<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        return response()->json($request->user());
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['sometimes','string','max:255'],
            'email'      => ['sometimes','email','max:255','unique:users,email,' . $user->id],

            'dob' => ['sometimes','nullable','date'],
            'height' => ['sometimes','nullable','numeric'],
            'weight' => ['sometimes','nullable','numeric'],
            'profile_image' => ['sometimes','nullable','string','max:2048'],
        ]);

        $user->update($data);

        return response()->json($user);
    }
}

// 'sometimes' allows the field to be optional, and if it's present, it will be validated according to the specified rules.