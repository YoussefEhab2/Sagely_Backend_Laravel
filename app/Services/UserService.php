<?php
namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

use App\Models\User;

class UserService
{
    protected UserRepository $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    public function register(array $data): array
    {
        $user = $this->users->create([
            'name'       => $data['name'],
            'email'      => $data['email'],
            'passwordHash' => Hash::make($data['password']),
            'role'       => 'student',
            'phoneNumber'=> $data['phoneNumber'] ?? null,
        ]);

        $token = JWTAuth::fromUser($user);

        return [
            'user'  => $user,
            'token' => $token,
        ];
    }
    public function login(array $data): array{
        $user = $this->users->findByEmail($data['email']);
        if(!$user || !Hash::check($data['password'], $user->passwordHash)){
            throw new \Exception("Invalid credentials");
        }
        $token = JWTAuth::fromUser($user);
        return [
            'user'  => $user,
            'token' => $token,
        ];
    }

    public function logout(): array{
        $this->users->logout();
        return ['message' => 'Successfully logged out'];
    }
}