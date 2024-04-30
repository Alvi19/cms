<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Auth"},
     *     summary="Auth login",
     *     description="Authenticate user and generate token",
     *     operationId="auth/login",
     *     @OA\RequestBody(
     *          required=true,
     *          description="form login",
     *          @OA\JsonContent(
     *              required={"email"},
     *              @OA\Property(property="email_or_phone", type="string"),
     *              @OA\Property(property="password", type="string"),
     *              ),     
     * ),
     *     @OA\Parameter(
     *         name="email_or_phone",
     *         in="query",
     *         description="email or phone number",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="password",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="Login successful"),
     *     @OA\Response(response="401", description="Invalid credentials")
     * )
     */
    public function login(Request $request)
    {
        $validate = $request->validate([
            'email_or_phone' => 'required', 'password' => 'required'
        ]);

        $success = false;

        if (filter_var($validate['email_or_phone'], FILTER_VALIDATE_EMAIL)) {

            $validate = $request->validate(['email_or_phone' => 'required|email|exists:users,email', 'password' => 'required']);
            $success = Auth::attempt(['email' => $validate['email_or_phone'], 'password' => $validate['password']]);
        } else {

            $validate = $request->validate(['email_or_phone' => 'required|exists:users,phone', 'password' => 'required']);
            $success = Auth::attempt(['phone' => $validate['email_or_phone'], 'password' => $validate['password']]);
        }

        if (!$success) {

            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials',
            ], 401);
        } else {

            $auth = Auth::user();
            $token = $auth->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'data' => [
                    'token' => $token,
                    'user' => $auth
                ]
            ], 200);
        }
    }
}
