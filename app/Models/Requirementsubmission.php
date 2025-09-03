<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Requirementsubmission
 * 
 * @property int $requirementID
 * @property int $studentID
 * @property string|null $fileUrl
 * 
 * @property Requirement $requirement
 * @property User $user
 *
 * @package App\Models
 */
class Requirementsubmission extends Model
{
	protected $table = 'requirementsubmission';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'requirementID' => 'int',
		'studentID' => 'int'
	];

	protected $fillable = [
		'fileUrl'
	];

	public function requirement()
	{
		return $this->belongsTo(Requirement::class, 'requirementID');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'studentID');
	}
}
