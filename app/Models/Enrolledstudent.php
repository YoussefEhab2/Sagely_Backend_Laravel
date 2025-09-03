<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Enrolledstudent
 * 
 * @property int $courseID
 * @property int $studentID
 * 
 * @property Course $course
 * @property User $user
 *
 * @package App\Models
 */
class Enrolledstudent extends Model
{
	protected $table = 'enrolledstudents';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'courseID' => 'int',
		'studentID' => 'int'
	];

	public function course()
	{
		return $this->belongsTo(Course::class, 'courseID');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'studentID');
	}
}
