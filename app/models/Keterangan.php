<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Keterangan extends Model
{
	protected $table = 'keterangan';
	public $timestamps = false;
	protected $fillable = [
		'idcube', 'judul', 'posx', 'posy', 'posz', 'deskripsi',
	]; 
	
}