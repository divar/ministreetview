<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class User extends Model
{
	protected $table = 'users';
	public $timestamps = true;
	protected $fillable = [
		'email', 'username', 'shadow',
	];
	public function setShadow($shadow){
		$this->update([
			'shadow'=>password_hash($shadow, PASSWORD_DEFAULT, ['cost'=>12])
		]);
	}

	public function Permission($primary){ 
		return $this->hasOne('App\Models\User_Permission','user_id',$primary);
	}
}