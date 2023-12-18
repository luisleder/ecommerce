<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Store a newly created resource in storage.
     * @param UserRequest
     * @unauthenticated
     * @status 204
     */
    public function store(UserRequest $request)
    {
        try {

            $request->validated();

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);

            return response(status:201);

        } catch (ValidationException $e) {

            return response()->json([
                'message' => 'Error in user creation',
                'errors' => $e->errors()
            ], 404);

        }

    }
}
