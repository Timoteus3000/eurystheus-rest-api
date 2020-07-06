<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\Request;
use App\User;

class JWTAuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|between:2,128',
            'last_name' => 'required|between:2,128',
            'email' => 'required|email|unique:tb_users|max:255',
            'password' => 'required|confirmed|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create(array_merge(
                    $validator->validated(),
                    ['password' => bcrypt($request->password)]
                ));

        return response()->json([
            'message' => 'Successfully registered',
            'user' => $user
        ], 201);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
    	$validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'password' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (! $token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->createNewToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }

    public function update(Request $request, $id) {
        $noChanges = true;

        $user = User::find(auth()->user()->pk_user);
        if($id != $user->pk_user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        if(!is_null($request->first_name) && !empty($request->first_name) && $user->first_name != $request->first_name) {
            $user->first_name = $request->first_name;
            $noChanges = false;
        }
        if(!is_null($request->last_name) && !empty($request->last_name) && $user->last_name != $request->last_name) {
            $user->last_name = $request->last_name;
            $noChanges = false;
        }
        if(!(Validator::make($request->all(), ['email' => 'required|email|unique:tb_users|max:255'])->fails()) && $user->email != $request->email) {
            $user->email = $request->email;
            $noChanges = false;
        }
        if(
            (!is_null($request->password) && !empty($request->password)) && 
            (!is_null($request->password_confirmation) && !empty($request->password_confirmation)) &&
            ($request->password == $request->password_confirmation)
        ) {
            $user->password = bcrypt($request->password);
            $noChanges = false;
        }

        $user->save();

        return response()->json([
            'message' => $noChanges ? 'Nothing to update.' : 'Successfully updated',
            'user' => $user
        ], 200);
    }

    public function delete(Request $request, $id) {
        $user = User::find(auth()->user()->pk_user);
        if($id != $user->pk_user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $user->delete();
        return response()->json([
            'message' => 'Successfully deleted'
        ], 200);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
