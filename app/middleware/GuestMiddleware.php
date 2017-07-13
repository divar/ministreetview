<?php 

namespace App\Middleware;

/**
* 
create by divar jati 
*/
class GuestMiddleware extends Middleware
{
	public function __invoke($request, $response, $next){
		 
		 if($this->container->auth->check()){
		 	$this->container->flash->addMessage('info','anda telah login');
		 	return $response->withRedirect($this->container->router->pathFor('home'));
		 }

		$response = $next($request, $response);
		return $response;
	}
}