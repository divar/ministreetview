<?php
use Slim\App;
use Respect\Validation\Validator as v;
 
session_start();

define('INC_ROOT',dirname(__DIR__));
require INC_ROOT.'/vendor/autoload.php';

$databaselocal = ([
	'driver' => 'mysql',
	'host' => 'localhost',
	'database' =>'campusstreetview',
	'username' => 'root',
	'password' => '',
	'charset' => 'utf8',
	'collation' => 'utf8_unicode_ci',
	'prefix' =>''
]);	
$databaseserver = ([
	'driver' => 'mysql',
	'host' => 'mysql.idhostinger.com',
	'database' =>'u615111913_msv',
	'username' => 'u615111913_msv',
	'password' => '6nnHIMp90',
	'charset' => 'utf8',
	'collation' => 'utf8_unicode_ci',
	'prefix' =>''
]);

$app = new App([
	'settings'=>[
	 	'determineRouteBeforeAppMiddleware' => true, 
   	 	'addContentLengthHeader' => false,
		'displayErrorDetails'=> true,
		'db'=>$databaselocal,
	]
]);

$container = $app->getContainer();

$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container['view'] = function($container){ 
	$view = new \Slim\Views\Twig(__DIR__.'/../resources/views',['cache'=> false,]);
	$view->addExtension(new \Slim\Views\TwigExtension(
			$container->router,
			$container->request->getUri()
		)); 
	$view->getEnvironment()->addGlobal('auth',[
		'check' => $container->auth->check(),
		'user' => $container->auth->user(), 
		'userlevel' => $container->auth->UserLevel(), 
		'INC_ROOT' => INC_ROOT,
	]); 
	$view->getEnvironment()->addGlobal('flash', $container->flash); 
	return $view;
};

$container['db']=function($container)use($capsule){
	return $capsule;
};

$container['auth'] = function($container){
	return new \App\Appauth\Auth;
};

$container['ChangePasswordController'] = function($container){
	return new \App\Controllers\Auth\ChangeShadowController($container);
};

$container['flash'] = function($container){
	return new \Slim\Flash\Messages;
};

$container['HomeController'] = function($container){
	return new \App\Controllers\HomeController($container);
};

$container['AuthController'] = function($container){
	return new \App\Controllers\Auth\AuthController($container);
};

$container['TableOfMap'] = function($container){
	return new \App\Controllers\Aflog\TableOfMap($container);
};
$container['TableOfUser'] = function($container){
	return new \App\Controllers\Aflog\TableOfUser($container);
};

$container['validator']=function($container){
	return new \App\validation\Validator;
};

$container['csrf'] = function($container){
	return new \Slim\Csrf\Guard;
}; 

$container['Content'] = function($container){
	return new \App\Controllers\Content\Content;
};
$container['TableContent'] = function($container){
	return new \App\Controllers\Content\TableContent;
};
$container['InputCube'] = function($container){
	return new \App\Controllers\Aflog\InputCube($container);
};
$container['InputKeterangan'] = function($container){
	return new \App\Controllers\Aflog\InputKeterangan($container);
};
$container['TableKeterangan'] = function($container){
	return new \App\Controllers\Aflog\TableOfKeteranganMap($container);
};
$container['TableOfKategori'] = function($container){
	return new \App\Controllers\Aflog\TableOfKategori($container);
};
$container['UpdateCubeMap'] = function($container){
	return new \App\Controllers\Aflog\UpdateCubeMap($container);
};
 
$app->add(new \App\Middleware\ValidationErrorsMiddleware($container));
$app->add(new \App\Middleware\OldInputMiddleware($container));
$app->add(new \App\Middleware\CsrfViewMiddleware($container));

$app->add($container->csrf);

$container['notFoundHandler'] = function ($container) {
    return function ($request, $response) use ($container) {
        return $container['view']->render($response,'404.twig')->withStatus(404);
    };
};

v::with('App\\Validation\\Rules\\');

require __DIR__.'/../app/route.php';
?>