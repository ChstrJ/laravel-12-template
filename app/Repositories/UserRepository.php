<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;

class UserRepository extends BaseRepository
{
    protected $table = 'users';
    protected $column = 'id';

    protected array $fillable = [
        'id',
        'name',
        'email',
        'user_type',
        'email_verified_at',
        'password',
        'remember_token',
        'created_at',
        'updated_at',
    ];
}