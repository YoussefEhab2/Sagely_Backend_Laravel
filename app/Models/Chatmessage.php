<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Chatmessage
 * 
 * @property int $id
 * @property int $senderID
 * @property int $receiverID
 * @property string $messageText
 * @property Carbon|null $timestamp
 * @property bool|null $isEscalated
 * 
 * @property User $user
 * @property Escalatedmessage|null $escalatedmessage
 *
 * @package App\Models
 */
class Chatmessage extends Model
{
	protected $table = 'chatmessage';
	public $timestamps = false;

	protected $casts = [
		'senderID' => 'int',
		'receiverID' => 'int',
		'timestamp' => 'datetime',
		'isEscalated' => 'bool'
	];

	protected $fillable = [
		'senderID',
		'receiverID',
		'messageText',
		'timestamp',
		'isEscalated'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'receiverID');
	}

	public function escalatedmessage()
	{
		return $this->hasOne(Escalatedmessage::class, 'messageID');
	}
}
