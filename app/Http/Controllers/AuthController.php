<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     *
     * @var AuthService
     */
    protected AuthService $service;
    /**
     *
     * @param \App\Services\AuthService $service
     */
    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }
    /**
     *
     * @param \App\Http\Requests\Auth\RegisterRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        $user = $this->service->register($request->validated());
        $token = $user->createToken('api-token')->plainTextToken;
        return response()->json(['user' => $user, 'token' => $token], Response::HTTP_CREATED);
    }
    /**
     *
     * @param \App\Http\Requests\Auth\LoginRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $user = $this->service->login($request->email, $request->password);
        $token = $user->createToken('api-token')->plainTextToken;
        return response()->json(['user' => $user, 'token' => $token], Response::HTTP_OK);
    }
     /**
      * 
      * @return mixed|\Illuminate\Http\JsonResponse
      */
     public function logout()
    {
       Auth::user()->currentAccessToken()->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'User is logged out successfully'
        ], Response::HTTP_OK);
    }
}
