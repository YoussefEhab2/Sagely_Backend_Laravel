<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Escalatedmessage
 * 
 * @property int $messageID
 * @property bool|null $Responded
 * 
 * @property Chatmessage $chatmessage
 *
 * @package App\Models
 */
class Escalatedmessage extends Model
{
	protected $table = 'escalatedmessage';
	protected $primaryKey = 'messageID';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'messageID' => 'int',
		'Responded' => 'bool'
	];

	protected $fillable = [
		'Responded'
	];

	public function chatmessage()
	{
		return $this->belongsTo(Chatmessage::class, 'messageID');
	}
}
