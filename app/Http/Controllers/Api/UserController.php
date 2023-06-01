<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * List all Users
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $users = User::all();

        return response()->json($users);
    }

    /**
     * Create a new User
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $request->merge([
                'password' => bcrypt($request->password)
            ]);
            $user = User::create($request->all());
            $token = $user->createToken(getDevice());

            return ['user' => $user->toArray(), 'access_token' => $token->toArray()];
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update a User
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     */
    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            if (!($request->user()->isAdmin() || $request->user()->id == $user->id)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $user->update($request->all());

            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Show a User
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        $user = User::findOrFail($id);

        return response()->json($user);
    }

    /**
     * Delete a User
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     */
    public function destroy(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            if (!($request->user()->isAdmin() || $request->user()->id == $user->id)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $user->delete();

            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}