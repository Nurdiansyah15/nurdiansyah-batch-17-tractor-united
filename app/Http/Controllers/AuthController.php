<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Utils\ResponseFormator;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $fields = $request->validate([
                'email' => 'string|email|required',
                'password' => 'string|required'
            ]);
            $token = auth()->attempt($fields);
            if (!$token) {
                return ResponseFormator::create(401, "Unauthorized");
            }
            return ResponseFormator::create(200, "Success", ['email' => $fields['email'], 'token' => $token]);
        } catch (ValidationException $e) {
            return ResponseFormator::create(400, $e->errors());
        }
    }

    public function register(Request $request)
    {
        try {
            $fields = $request->validate([
                'username' => 'string|required',
                'email' => 'string|email|required',
                'password' => 'string|required'
            ]);

            if (User::where('email', $fields['email'])->exists()) {
                return ResponseFormator::create(400, "Email already exists");
            }

            User::create([
                'username' => $fields['username'],
                'email' => $fields['email'],
                'password' => bcrypt($fields['password'])
            ]);

            $token = auth()->attempt(['email' => $fields['email'], 'password' => $fields['password']]);

            return ResponseFormator::create(200, "Success", ['email' => $fields['email'], 'token' => $token]);
        } catch (ValidationException $e) {
            return ResponseFormator::create(400, $e->errors());
        }
    }
}
