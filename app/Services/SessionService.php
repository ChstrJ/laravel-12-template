<?php

namespace App\Services;

use Carbon\Carbon;
use App\Repositories\SessionRepository;
use Illuminate\Support\Str;
use App\Constants\Sessions;

class SessionService
{
    public function __construct(
        protected SessionRepository $sessionRepository
    ) {
    }

    public function find(string $sessionId)
    {
        $session = $this->sessionRepository->find($sessionId, 'session_id');

        if (! $session) {
            throw new \Exception('Session not found');
        }

        return $session;
    }

    public function findSessionByUserId(string $userId, bool $skip = false)
    {
        $session = $this->sessionRepository->find($userId, 'user_id', true);

        if (! $session && ! $skip) {
            throw new \Exception('Session not found');
        }

        return $session;
    }

    public function store(mixed $data)
    {
        if (! $data) {
            throw new \Exception('Missing data to store in session');
        }

        $existing = $this->findSessionByUserId($data->id, true);

        if ($existing) {
            $this->destroy($existing->session_id);
        }

        $sessionData = (object)[
            'id' => $data->id,
            'name' => $data->name,
            'email' => $data->email,
            'user_type' => $data->user_type,
        ];

        $insertData = [
            'session_id' => Str::uuid(),
            'user_id' => $data->id,
            'session_data' => json_encode($sessionData),
            'created_at' => Carbon::now()->timestamp,
        ];

        $session = $this->sessionRepository->insert($insertData);

        if (! $session) {
            throw new \Exception('Failed to store session');
        }

        return $sessionData;
    }

    public function destroy(string $sessionId)
    {
        $session = $this->find($sessionId);

        if (! $session) {
            throw new \Exception('Session not found');
        }

        return $this->sessionRepository->delete($sessionId);
    }
}
