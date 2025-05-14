<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;

class SessionRepository extends BaseRepository
{
    protected $table = 'sessions';
    protected $column = 'session_id';

    protected array $fillable = [
        'session_id',
        'user_id',
        'session_data',
        'last_activity',
        'created_at',
        'updated_at',
    ];
}