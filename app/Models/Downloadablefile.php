<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Downloadablefile
 * 
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string $fileUrl
 * @property int|null $courseID
 * 
 * @property Course|null $course
 *
 * @package App\Models
 */
class Downloadablefile extends Model
{
	protected $table = 'downloadablefiles';
	public $timestamps = false;

	protected $casts = [
		'courseID' => 'int'
	];

	protected $fillable = [
		'name',
		'description',
		'fileUrl',
		'courseID'
	];

	public function course()
	{
		return $this->belongsTo(Course::class, 'courseID');
	}
}
