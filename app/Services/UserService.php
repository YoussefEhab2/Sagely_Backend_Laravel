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

    public function changePassword(array $data): User
    {
       $user = auth('api')->user();

        if (!Hash::check($data['old_password'], $user->passwordHash)) {
            throw new Exception('Old password is incorrect');
        }

        return $this->users->changePassword($user, Hash::make($data['new_password']));
    }
    public function updateProfile(User $user, array $data): User
    {
      $updateData = [];

    if (isset($data['phoneNumber'])) {
        $updateData['phoneNumber'] = $data['phoneNumber'];
    }

    if (isset($data['emailNotificationPreferences'])) {
        $updateData['emailNotificationPreferences'] = (bool) $data['emailNotificationPreferences'];
    }

    if (isset($data['siteNotificationPreferences'])) {
        $updateData['siteNotificationPreferences'] = (bool) $data['siteNotificationPreferences'];
    }

    return $this->users->updateProfile($user, $updateData);
    }
}