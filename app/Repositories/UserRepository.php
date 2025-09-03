<?php
namespace App\Repositories;

use App\Models\User;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class UserRepository
{
     protected User $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }
    public function findByEmail(string $email): ?User{
        return $this->model->where("email", $email)->first();
    }
    public function logout(): void
    {
        JWTAuth::invalidate(JWTAuth::getToken());
    }
    public function create(array $data)
    {
        return User::create($data);
    }
}
