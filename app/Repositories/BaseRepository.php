<?php

namespace App\Repositories;

use Illuminate\Contracts\Redis\Connector;
use Illuminate\Database\Connection;

class BaseRepository
{
    protected $table = '';
    protected $column = '';
    public $connection;
    protected $builder = [];

    public function __construct(
        Connection $connection
    ) {
        $this->connection = $connection;
        $this->startBuild();
    }

    protected function startBuild($statement = '', $bindings = [])
    {
        $this->builder = [
            'statement' => $statement,
            'bindings' => $bindings,
        ];

        return $this;
    }

    public function find($data, $column = null, $skip = false)
    {
        if (! $column) {
            $column = $this->column;
        }

        $results = $this->connection
            ->table($this->table)
            ->where($column, $data)
            ->first();

        if (! $results && ! $skip) {
            throw new \Exception('Record not found');
        }

        return $results;
    }

    public function insert($data)
    {
        if (! $data) {
            throw new \Exception('Data is required');
        }

        $results = $this->connection
            ->table($this->table)
            ->insert($data);

        if (! $results) {
            throw new \Exception('Failed to insert data');
        }

        return $results;
    }

    public function delete($data)
    {
        if (! $data) {
            throw new \Exception('Please insert the proper data to delete');
        }

        $results = $this->connection
            ->table($this->table)
            ->where($this->column, $data)
            ->delete();

        if (! $results) {
            throw new \Exception('Failed to delete data');
        }

        return $results;
    }
}
