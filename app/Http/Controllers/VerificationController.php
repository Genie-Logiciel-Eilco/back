<?php

namespace App\Http\Controllers;

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
    
    public function resend() {
        if ($this->getAuthenticatedUser()->hasVerifiedEmail()) {
            return $this->sendError("Email already verified.", 400);
        }
    
        $this->getAuthenticatedUser()->sendEmailVerificationNotification();
    
        return $this->sendResponse([],"Email verification link sent on your email id");
    }
}
