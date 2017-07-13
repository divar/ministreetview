<?php
use App\Middleware\AuthMiddleware;
use App\Middleware\FilterImageMiddleware;
use App\Middleware\RemoceExcifMiddleware;
use App\Middleware\MoveImageMiddleware;
use App\Middleware\GuestMiddleware;

$app->get('/','HomeController:getTheCubeMap')->setName('home');
$app->get('/readas','ReadImage:readImage')->setName('readimg');

$app->group('',function(){
	$this->get('/auth/signup','AuthController:getSignUp')->setName('auth.signup');
	$this->post('/auth/signup','AuthController:postSignUp');

	$this->get('/auth/signin','AuthController:getSignIn')->setName('auth.signin');
	$this->post('/auth/signin','AuthController:postSignIn'); 
})->add(new GuestMiddleware($container));

$app->group('',function(){ 
	$this->get('/auth/changepassword','ChangePasswordController:getChangeShadow')->setName('auth.changepass');
	$this->post('/auth/changepassword','ChangePasswordController:postChangeShadow');

	$this->get('/auth/signout','AuthController:getSignOut')->setName('auth.signout');

	$this->get('/user/dashboard','TableOfMap:getDataMap')->setName('user.dashboard');
	$this->post('/user/dashboard','TableOfMap:postNewKategori');
	$this->get('/admin/deletecube','TableOfMap:deleteCubeMap')->setName('admin.deleteCubeMap');

	$this->get('/admin/kategori','TableOfKategori:getKategori')->setName('admin.kategori'); 
	$this->post('/admin/kategori','TableOfKategori:updateKategori');

	$this->get('/admin/dataUser','TableOfUser:getDataUser')->setName('admin.datauser');
	$this->post('/admin/dataUser','TableOfUser:updateDataUser');
	$this->get('/admin/deleteUser','TableOfUser:deleteUser')->setName('admin.deleteUser');

	$this->get('/admin/TableKeterangan','TableKeterangan:getDataKeterangan')->setName('admin.TableKeterangan');
	$this->post('/admin/TableKeterangan','TableKeterangan:postDataKeterangan');
	$this->get('/admin/delete','TableKeterangan:deleteKeterangan')->setName('admin.deleteKeterangan');

	$this->get('/form/inputcube','InputCube:getinputcube')->setName('form.inputcube');
	$this->post('/form/inputcube','InputCube:postinputcube');

	$this->get('/form/updatecube','UpdateCubeMap:getInputCube')->setName('form.updatecube');
	$this->post('/form/updatecube','UpdateCubeMap:postInputcube');

	$this->get('/form/InputKeterangan','InputKeterangan:getinputketerangan')->setName('form.inputketerangan');
	$this->get('/form/InputKeteranganp','InputKeterangan:postinputketerangan')->setName('form.postinputketerangan');
})->add(new AuthMiddleware($container));

// $app->group('',function(){

// })->add(new AuthAdminMiddleware($container));