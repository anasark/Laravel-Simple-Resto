<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activitys extends Model
{
	public $timestamps = false;

	protected $table = 'activitys';

	protected $fillable = [
	    'user_id', 'activity', 'time_at',
	];
}
