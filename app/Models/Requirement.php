<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Requirement
 * 
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property int $courseID
 * 
 * @property Course $course
 * @property Collection|Requirementsubmission[] $requirementsubmissions
 *
 * @package App\Models
 */
class Requirement extends Model
{
	protected $table = 'requirement';
	public $timestamps = false;

	protected $casts = [
		'courseID' => 'int'
	];

	protected $fillable = [
		'title',
		'description',
		'courseID'
	];

	public function course()
	{
		return $this->belongsTo(Course::class, 'courseID');
	}

	public function requirementsubmissions()
	{
		return $this->hasMany(Requirementsubmission::class, 'requirementID');
	}
}
