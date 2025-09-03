<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Course
 * 
 * @property int $id
 * @property string $name
 * @property string|null $description
 * 
 * @property Collection|Announcement[] $announcements
 * @property Collection|Downloadablefile[] $downloadablefiles
 * @property Collection|Enrolledstudent[] $enrolledstudents
 * @property Collection|Requirement[] $requirements
 *
 * @package App\Models
 */
class Course extends Model
{
	protected $table = 'course';
	public $timestamps = false;

	protected $fillable = [
		'name',
		'description'
	];

	public function announcements()
	{
		return $this->hasMany(Announcement::class, 'courseID');
	}

	public function downloadablefiles()
	{
		return $this->hasMany(Downloadablefile::class, 'courseID');
	}

	public function enrolledstudents()
	{
		return $this->hasMany(Enrolledstudent::class, 'courseID');
	}

	public function requirements()
	{
		return $this->hasMany(Requirement::class, 'courseID');
	}
}
