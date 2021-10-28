<?php


namespace App\Http\Controllers;

use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use App\Models\Role;
use App\Notifications\APIPasswordResetNotification;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use IlluminateSupportFacadesHash;
use IlluminateSupportFacadesPassword;
use IlluminateHttpRequest;
use IlluminateSupportFacadesValidator;

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
        ])->sendEmailVerificationNotification();
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
            if (!$user ) {
                return $this->sendError('User not found', 401);
            }
        }
        if(!Hash::check($fields['password'], $user->password))
        {
            return $this->sendError('Bad credentials', 401);
        }
        $token = $user->createToken('myapptoken')->plainTextToken;
        $role=Role::find($user->role_id);
        unset($user->role_id);
        $response = [
            'user' => $user,
            'role'=>$role->role_name,
            'token' => $token
        ];
        return $this->sendResponse($response, "");
    }
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $fields=$request->validated();
        $user=User::where('email',$fields['email'])->firstOrFail();
        $token=Password::createToken($user);
        $user->notify(new APIPasswordResetNotification($token));
        return $this->sendResponse($token,"done");
    }
    public function resetPassword(ResetPasswordRequest $request){
        $fields=$request->validated();
        $input = $request->only('email','token', 'password', 'password_confirmation');
        $user=User::where('email',$fields['email'])->firstOrFail();
        $response = Password::reset($input, function ($user, $password) {
            $user->password = Hash::make($password);
            $user->save();
            
        });
        if($response == Password::PASSWORD_RESET)
        {
            return $this->sendResponse([],'Password reset successfully');
        }
        if($response==Password::INVALID_TOKEN)
        {
            return $this->sendError("Invalid token");
        }
        return $this->sendError($response);
    }
    public function users($rowsPerPage)
    {
        return $this->sendResponse(User::paginate($rowsPerPage));
    }
}