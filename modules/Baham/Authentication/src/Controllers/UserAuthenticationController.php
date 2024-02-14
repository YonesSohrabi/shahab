<?php

namespace Baham\Authentication\Controllers;

use App\Http\Controllers\Controller;
use Baham\User\Contract\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use function resolve;
use function response;


class UserAuthenticationController extends Controller
{
    public function signUp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_name' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => $validator->messages()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {

            $user = resolve(UserRepositoryInterface::class)->firstOrCreateByUserName($request->user_name);

            Auth::loginUsingId($user->id);

            $token = $user->createToken("auth")->plainTextToken;

        } catch (\Throwable $exception) {

            return response()->json([
                'status' => false,
                'data' => null,
                'message' => $exception->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => true,
            'data' => [
                'token' => $token,
            ],
            'message' => 'The user token was successfully generated.'
        ], Response::HTTP_OK);

    }


    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
        } catch (\Throwable $exception) {

            return response()->json([
                'status' => false,
                'data' => null,
                'message' => $exception->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => true,
            'data' => null,
            'message' => 'User token deleted successfully.'
        ], Response::HTTP_OK);
    }

}
