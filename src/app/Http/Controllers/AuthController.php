<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use App\Http\Resources\SignupResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    /**
     * Method for user authorization
     *
     * @api
     * @param  LoginRequest  $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request)
    {
        if(Auth::attempt($request->all())){
            Auth::user()->tokens()->delete();
            return response()->json([
                'data'=>[
                    'status' => true,
                    'token'  => Auth::user()->createToken('Api')->plainTextToken
                ]
            ])->setStatusCode(200, 'Successful authorization');
        }
        else{
            return response()
                ->json([
                    'status'  => false,
                    'message' => 'Invalid authorization data',
                ])
                ->setStatusCode(Response::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * Method for logout
     *
     * @api
     * @return JsonResponse
     */
    public function logout()
    {
        Auth::user()->tokens()->delete();
        return response()
            ->json([
                'message' => 'Logout',
            ])
            ->setStatusCode(Response::HTTP_OK);
    }
}
