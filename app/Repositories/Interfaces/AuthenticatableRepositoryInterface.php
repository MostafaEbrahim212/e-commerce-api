<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface AuthenticatableRepositoryInterface
{
    public function create(array $data);
    public function findByEmail(string $email);
    public function validateCredentials($model, string $password);
    public function generateToken($model);
    public function logout($model);
}
