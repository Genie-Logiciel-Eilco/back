<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResendEmailVerification;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class VerificationController extends Controller
{
    public function verify($user_id, Request $request) {
        if (!$request->hasValidSignature()) {
            return $this->sendError("Invalid Url or Signature",401);
        }
    
        $user = User::findOrFail($user_id);
    
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }
        else
        {
            return $this->sendError("Email is already verified", 400);

        }
    
        return redirect()->away(Config::get("app.url_front"));
    }
    
    public function resend(ResendEmailVerification $request) {
        $fields=$request->validated();
        $user=User::where('email',$fields['email'])->firstOrFail();
        if ($user->hasVerifiedEmail()) {
            return $this->sendError("Email already verified.", 400);
        }
    
        $user->sendEmailVerificationNotification();
    
        return $this->sendResponse([],"Email verification link sent on your email id");
    }
}
