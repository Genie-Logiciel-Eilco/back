<?php


namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $fields = $request->validated();
        Role::findOrFail($fields['role_id']);
        User::create([
            'first_name'=>$fields['first_name'],
            'last_name'=>$fields['last_name'],
            'username'=>$fields['username'],
            'email'=>$fields['email'],
            'password'=>bcrypt($fields['password']),
            'role_id'=>$fields['role_id']
        ]);
        return $this->sendResponse([],"Account created successfully");
    }

    public function logout()
    {
        $this->getAuthenticatedUser()->tokens()->delete();
        return $this->sendResponse([], "");
    }
    public function login(LoginRequest $request)
    {
        $fields = $request->validated();
        
        $user = User::where(DB::raw('LOWER(`username`)'), strtolower(trim($fields['usernameOrEmail'])))->first();
        if(!$user)
        {
            $user = User::where(DB::raw('LOWER(`email`)'), strtolower(trim($fields['usernameOrEmail'])))->first();
            if (!$user || !Hash::check($fields['password'], $user->password)) {
                return $this->sendError('Bad credentials', 401);
            }
        }
        $token = $user->createToken('myapptoken')->plainTextToken;
        $role=Role::find($user->role_id);
        unset($user->role_id);
        $response = [
            'user' => $user,
            'role'=>$role->role_name,
            'token' => $token,
            'first_time' => $user->code_pin == null,
        ];
        return $this->sendResponse($response, "");
    }


}