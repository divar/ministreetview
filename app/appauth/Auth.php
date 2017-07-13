<?php

namespace App\Appauth;
/**
* create by divar jati
*/
use App\Models\User;
use App\Models\User_Permission;
class Auth 
{
	// protected $dum;
	public function user(){
		if(isset($_SESSION['user'])){
			return User::find($_SESSION['user']);
		}
	}

	public function check(){
		return isset($_SESSION['user']);
	}
	public function attempt($email, $shadow){
		
		$user = User::where('email', $email)->first();
		
		if(!$user){
			return false;
		}

		if(password_verify($shadow, $user->shadow)){
			$_SESSION['user'] = $user->id; 
			return true;
		}
		return false;
	}
	public function logout(){
		unset($_SESSION['user']);
	}

	public function userLevel(){
		// return $this->Permission($_SESSION['user']);
		if(isset($_SESSION['user'])){
			$dum = User_Permission::where('user_id', $_SESSION['user'])->first();
			return $dum->user_level; 
		}
	}

}
