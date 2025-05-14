<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Constants\Users;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {  
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'user_type' => ['required', Rule::in(Users::VALID_USER_TYPES)],
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return responseOne($validator->errors()->first(), 400);
        }

        return responseOne($this->userService->register($request->all()));
    }
    
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return responseOne($validator->errors()->first(), 400);
        }

        return responseOne($this->userService->login($request->email, $request->password));
    }

    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
        ]);

        if ($validator->fails()) {
            return responseOne($validator->errors()->first(), 400);
        }

        return responseOne($this->userService->destroy($request->all()));
    }
}
