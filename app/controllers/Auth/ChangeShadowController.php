<?php

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use App\Models\User; 
use Respect\Validation\Validator as v;

Class ChangeShadowController extends Controller
{ 
	//get the view form
	Public function getChangeShadow($request, $response){
		return $this->view->render($response, 'auth/changepassword.twig');
	}
	//get the view form
	Public function postChangeShadow($request, $response){
		//validation
		$validation = $this->validator->validate($request, [
			'shadow_old'=>v::notEmpty()->matchesShadow($this->auth->user()->shadow),
			'shadow'=>v::notEmpty(),
		]);
		//validation checking if returning not valid or false
		if($validation->failed()){
			return $response->withRedirect($this->router->pathFor('auth.changepass'));
		}
		//if validation is true, sett the password into database using setShadow-function from Models\user
		$this->auth->user()->setShadow($request->getParam('shadow'));
		$this->flash->addMessage('info','password anda telah diubah.');
		return $response->withRedirect($this->router->pathFor('home'));
	}
}