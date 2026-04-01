<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();
        return response()->json($this->formatUser($user));
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name'          => ['sometimes', 'string', 'max:255'],
            'email'         => ['sometimes', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'dob'           => ['sometimes', 'nullable', 'date'],
            'height'        => ['sometimes', 'nullable', 'numeric'],
            'weight'        => ['sometimes', 'nullable', 'numeric'],
            'profile_image' => ['sometimes', 'nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }

            $path = $request->file('profile_image')->store('profile-images', 'public');
            $data['profile_image'] = $path;
        }

        $user->update($data);

        return response()->json($this->formatUser($user->fresh()));
    }

    // Returns the user with a full URL for profile_image instead of just the storage path
    private function formatUser($user): array
    {
        $data = $user->toArray();
        $data['profile_image'] = $user->profile_image
            ? rtrim(config('app.url'), '/') . '/storage/' . $user->profile_image
            : null;
        return $data;
    }
}