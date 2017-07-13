<?php 

namespace App\Middleware;

/**
* 
create by divar jati 
*/
class AuthMiddleware extends Middleware
{
	public function __invoke($request, $response, $next){
		 
		 if(!$this->container->auth->check()){
		 	$this->container->flash->addMessage('error','silahkan login dahulu');
		 	return $response->withRedirect($this->container->router->pathFor('auth.signin'));
		 }

		$response = $next($request, $response);
		return $response;
	}
}