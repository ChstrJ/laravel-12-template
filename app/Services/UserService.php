<?php

namespace App\Services;

use App\Constants\Users;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
use App\Repositories\UserRepository;
use App\Services\SessionService;

class UserService
{
    public function __construct(
        protected UserRepository $userRepository,
        protected SessionService $sessionService
    ) {
    }

    public function register(array $request)
    {
        $checkEmail = $this->userRepository->find($request['email'], 'email', true);

        if ($checkEmail) {
            throw new \Exception('Email already exists');
        }

        $data = [
            'id' => Str::uuid(),
            'name' => $request['name'],
            'email' => $request['email'],
            'user_type' => $request['user_type'],
            'password' => Hash::make($request['password']),
            'created_at' => Carbon::now()->timestamp,
        ];

        $this->userRepository->insert($data);

        return $this->login($request['email'], $request['password']);
    }

    public function login(string $email, string $password)
    {
        $user = $this->userRepository->find($email, 'email');

        if (!$user || !Hash::check($password, $user->password)) {
            throw new \Exception('Invalid credentials');
        }

        return $this->sessionService->store($user);
    }

    public function destroy(array $data)
    {
        $user = $this->userRepository->find($data['email'], 'email');

        if (!$user) {
            throw new \Exception('User not found');
        }

        return $this->userRepository->delete($user->id);
    }
}
