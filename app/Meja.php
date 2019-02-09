<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Meja extends Model
{
    protected $fillable = [
	    'id', 'nomor', 'status',
	];
}
