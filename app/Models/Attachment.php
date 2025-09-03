<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Attachment
 * 
 * @property int $id
 * @property int $announcementID
 * @property string $fileUrl
 * 
 * @property Announcement $announcement
 *
 * @package App\Models
 */
class Attachment extends Model
{
	protected $table = 'attachment';
	public $timestamps = false;

	protected $casts = [
		'announcementID' => 'int'
	];

	protected $fillable = [
		'announcementID',
		'fileUrl'
	];

	public function announcement()
	{
		return $this->belongsTo(Announcement::class, 'announcementID');
	}
}
