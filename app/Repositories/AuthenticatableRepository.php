<?php

namespace App\Repositories;

use App\Repositories\Interfaces\AuthenticatableRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class AuthenticatableRepository implements AuthenticatableRepositoryInterface
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function findByEmail(string $email)
    {
        return $this->model->where('email', $email)->first();
    }

    public function validateCredentials($model, string $password)
    {
        return $model && Hash::check($password, $model->password);
    }

    public function generateToken($model)
    {
        return $model->createToken('authToken')->plainTextToken;
    }

    public function logout($model)
    {
        $model->tokens()->delete();
    }
}
