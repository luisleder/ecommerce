<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{

    /**
     * Create new login
     * @param Request $request
     * 
     */
    public function login(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];
        
        try {
            
            $request->validate($rules);
            $credentials = $request->only('email', 'password');
            
            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $token = $user->createToken('user token');
                return response()->json([
                    'access_token' => $token->accessToken,
                    'token_type' => 'Bearer',                    
                    'expires_at' => Carbon::parse($token->token->expires_at)->toDateTimeString()
                ]);
    
                return response()->json($token);
            } else {
                throw new \Exception("User or password are not valid", 404);
            }
            
        
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 404);
        } catch (\Exception $e){
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }

    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $rules = [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
        ];

        try {

            $request->validate($rules);

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);

            return response()->json([
                'message' => 'Successfully created user!'
            ], 201);

        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 404);
        }

    }
}
