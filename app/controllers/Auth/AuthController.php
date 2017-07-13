<?php

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use App\Models\User; 
use App\Models\User_Permission; 
use Respect\Validation\Validator as v;

Class AuthController extends Controller
{
	public function getSignout($request, $response){
		$this->auth->logout();
		return $response->withRedirect($this->router->pathFor('home'));
	}

	public function getSignIn($request, $response){
		return $this->view->render($response, 'auth/signin.twig');
	}

	public function postSignIn($request, $response){
		$auth = $this->auth->attempt(
			$request->getParam('email'),
			$request->getParam('shadow')
		);	 	
		
		if(!$auth){
			$this->flash->addMessage('error','Maaf Username atau Password yang anda masukan salah !');
			return $response->withRedirect($this->router->pathFor('auth.signin'));
		} 

		$this->flash->addMessage('info','Selamat anda berhasil Login !');
		return $response->withRedirect($this->router->pathFor('user.dashboard'));
	}
	

	public function getSignUp($request, $response){
		return $this->view->render($response,'auth/signup.twig');
	}


	public function postSignUp($request, $response){
		$validation = $this->validator->validate($request,[
			'username' => v::notEmpty()->alpha(),
			'email' => v::noWhitespace()->notEmpty()->email()->emailAvailable(),
			'shadow' => v::notEmpty(),
		]); 
		if($validation->failed()){
			return $response->withRedirect($this->router->pathFor('auth.signup'));
		}

		$user = User::create([ 
				'username'=> $request->getParam('username'),
				'email'=> $request->getParam('email'),
				'shadow'=> password_hash($request->getParam('shadow'), PASSWORD_DEFAULT, ['cost'=>12]),
			]);

		$user->permission()->create(User_Permission::$defaults); 
		$this->flash->addMessage('info','anda telah terdaftar');
		$this->auth->attempt($user->email, $request->getParam('shadow'));
		return $response->withRedirect($this->router->pathFor('home'));
	}
}