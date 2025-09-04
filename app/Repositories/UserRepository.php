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

    public function changePassword(User $user, string $hashedPassword): User
    {
        $user->passwordHash = $hashedPassword;
        $user->save();
        return $user;
}

public function updateProfile(User $user, array $data): User
{
    $user->update($data);
    return $user;
}
}