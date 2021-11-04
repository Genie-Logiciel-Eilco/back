<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function sendResponse($result, $message = "",$status=200)
    {
        return Response::json($this->makeResponse($message, $result),$status,[],JSON_UNESCAPED_SLASHES);
    }

    public function sendError($error, $code = 404, $data = null)
    {
        return Response::json($this->makeError($error, $data), $code);
    }

    // Permissions related

    public function getAuthenticatedUser()
    {
        // The routes that are going to be using this should be under the auth:sanctum middelware
        try {
            // This should probably work if Sactum is well integrated
            if (!$userModel = auth()->user()) {
                return false;
            }
        } catch (\Exception $e) {
            // Catch multiple exceptions and return appropriate error messages...
            return false;
        }
        $user = \App\Models\User::find($userModel->id);
        return $user;
    }

    public function verifyPermission($perm)
    {
        // This uses spatie's roles and permissions package
        $user = $this->getAuthenticatedUser();
        if (!$user || !$user->hasPermissionTo($perm)) {
            return false;
        }
        return $user;
    }
    public function hasRole($role)
    {
         // This uses spatie's roles and permissions package
         $user = $this->getAuthenticatedUser();
         if (!$user || !$user->hasRole($role)) {
            return false;
        }
        return $user;
    }

    public function permissionDenied()
    {
        return $this->sendError('Permission denied', 403);
    }

    // Private helpers

    private function makeResponse($message, $data)
    {
        return [
            'success' => true,
            'data' => $data,
            'message' => $message,
        ];
    }

    private function makeError($message, $data = null)
    {
        $res = [
            'success' => false,
            'message' => $message,
            'data' => $data
        ];

        return $res;
    }
}
