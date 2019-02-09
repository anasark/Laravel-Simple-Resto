<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EntranceLog extends Model
{
	public $timestamps = false;

	protected $table = 'entrance_logs';

	protected $fillable = [
	    'user_id', 'loged_in_at', 'loged_out_at', 'login_hours',
	];
}
