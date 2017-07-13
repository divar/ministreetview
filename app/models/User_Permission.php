<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class User_Permission extends Model
{
	protected $table = 'userpermission';
	public $timestamps = true;
	protected $fillable = [
		'user_level' 
	];
	public static $defaults = ['user_level'=>3];
}