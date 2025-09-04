<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
/**
 * Class User
 * 
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $passwordHash
 * @property string $role
 * @property string|null $phoneNumber
 * @property bool|null $emailNotificationPreferences
 * @property bool|null $siteNotificationPreferences
 * 
 * @property Collection|Chatmessage[] $chatmessages
 * @property Collection|Enrolledstudent[] $enrolledstudents
 * @property Collection|Notification[] $notifications
 * @property Collection|Requirementsubmission[] $requirementsubmissions
 *
 * @package App\Models
 */
class User extends Authenticatable implements JWTSubject
{
	use Notifiable;
	protected $table = 'user';
	public $timestamps = false;

	protected $casts = [
		'emailNotificationPreferences' => 'bool',
		'siteNotificationPreferences' => 'bool'
	];

	protected $fillable = [
		'name',
		'email',
		'passwordHash',
		'role',
		'phoneNumber',
		'emailNotificationPreferences',
		'siteNotificationPreferences'
	];
	 public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
	public function getAuthPassword()
{
    return $this->passwordHash;
}

	public function chatmessages()
	{
		return $this->hasMany(Chatmessage::class, 'receiverID');
	}

	public function enrolledstudents()
	{
		return $this->hasMany(Enrolledstudent::class, 'studentID');
	}

	public function notifications()
	{
		return $this->hasMany(Notification::class, 'recipientID');
	}

	public function requirementsubmissions()
	{
		return $this->hasMany(Requirementsubmission::class, 'studentID');
	}
}
