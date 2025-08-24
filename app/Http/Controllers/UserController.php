<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // Return JSON for DataTables
    public function datatable()
    {
        $users = User::select('id', 'name', 'email', 'created_at')->get();

        $data = $users->map(function ($user) {
            return [
                'id'     => $user->id,
                'name'   => $user->name,
                'email'  => $user->email,
                'created_at' => $user->created_at->format('Y-m-d'),
                'actions' => '<button class="btn btn-sm btn-primary editUser"
                                data-id="' . $user->id . '"
                                data-name="' . $user->name . '"
                                data-email="' . $user->email . '">Edit</button>',
            ];
        });

        return response()->json(['data' => $data]);
    }

    // Update user via AJAX
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id)
            ],
        ]);

        $user->update($validated);

        return response()->json(['message' => 'User updated successfully']);
    }
}
