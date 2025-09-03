<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Announcement
 * 
 * @property int $id
 * @property string $title
 * @property string $content
 * @property string|null $category
 * @property Carbon $publishDate
 * @property int|null $courseID
 * 
 * @property Course|null $course
 * @property Collection|Attachment[] $attachments
 *
 * @package App\Models
 */
class Announcement extends Model
{
	protected $table = 'announcement';
	public $timestamps = false;

	protected $casts = [
		'publishDate' => 'datetime',
		'courseID' => 'int'
	];

	protected $fillable = [
		'title',
		'content',
		'category',
		'publishDate',
		'courseID'
	];

	public function course()
	{
		return $this->belongsTo(Course::class, 'courseID');
	}

	public function attachments()
	{
		return $this->hasMany(Attachment::class, 'announcementID');
	}
}
