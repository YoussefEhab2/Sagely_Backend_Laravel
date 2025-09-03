<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Notification
 * 
 * @property int $id
 * @property string $type
 * @property string $message
 * @property int|null $recipientID
 * @property string|null $status
 * 
 * @property User|null $user
 *
 * @package App\Models
 */
class Notification extends Model
{
	protected $table = 'notification';
	public $timestamps = false;

	protected $casts = [
		'recipientID' => 'int'
	];

	protected $fillable = [
		'type',
		'message',
		'recipientID',
		'status'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'recipientID');
	}
}
