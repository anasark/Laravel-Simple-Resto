<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
	protected $fillable = [
	    'nomor', 'meja', 'pesanan', 'jumlah_total', 'status', 'user_id'
	];
}
