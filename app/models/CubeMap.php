<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class CubeMap extends Model
{
	protected $table = 'CubeMap';
	public $timestamps = false;
	protected $fillable = [
		'id_cube', 'id_kategori', 'nama_tempat', 'deskripsi', 'user_level', 'map1', 'map2', 'map3', 'map4', 'map5', 'map6'
	];
	public function relationKeterangan($primary){ 
		return $this->hasOne('App\Models\keterangan','id_cube',$primary);
	} 
}