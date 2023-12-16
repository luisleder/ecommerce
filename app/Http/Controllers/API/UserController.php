<?php

namespace App\Http\Controllers\API;

use App\Exceptions\UserException;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserAuthRequest;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{

    /**
     * Create new login
     * @param Request UserAuthRequest
     * @response array{access_token: string, token_type: string, expires_at: string}
     * 
     */
    public function login(UserAuthRequest $request)
    {
        try {
            
            $request->validated();
            $credentials = $request->only('email', 'password');
            
            if (Auth::attempt($credentials)){

                $user_id = Auth::user()->id;
                $user = User::find($user_id);
                $token = $user->createToken("test mailup");
    
                return response()->json([
                    'access_token' => $token->accessToken,
                    'token_type' => 'Bearer',                    
                    'expires_at' => Carbon::parse($token->token->expires_at)->toDateTimeString()
                ]);

            }else{

                throw UserException::notExists();

            }
            
        
        } catch (ValidationException $e) {

            return response()->json([
                'message' => 'Request error',
                'errors' => $e->errors()
            ], 404);

        }

    }


    /**
     * Store a newly created resource in storage.
     * @param UserAuthRequest
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
