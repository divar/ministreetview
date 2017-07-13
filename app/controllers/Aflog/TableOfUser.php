<?php

namespace App\Controllers\Aflog;

use App\Controllers\Controller; 
use App\Controllers\Content\Content; 
use App\Controllers\Content\TableContent; 

Class TableOfUser extends Controller
{
	protected $userlevel;
	public function getDataUser($request, $response){
		if($this->container->auth->check()){
			$this->userlevel = $this->container->auth->UserLevel();
			if($this->userlevel>0){
				return $response->withRedirect($this->router->pathFor('user.dashboard'));	
			}
			$this->Content->setSideBarContent(0);
			$datanav = $this->Content->getSideBarContent();
		}
		$this->storingDataToGlobal();
		return $this->view->render($response,'afterlogin/TableUser.twig');
	}
	public function updateDataUser($request, $response){
		$update = $this->TableContent->updateUserLevel($request->getParam('userId'),$request->getParam('userLevel'));
		if ($update==true) {
			return $response->withRedirect($this->router->pathFor('admin.datauser'));
		} 
	} 
	public function deleteUser($request, $response){
		$this->TableContent->deleteUser($request->getParam('id'));
		return $response->withRedirect($this->router->pathFor('admin.datauser'));
	}
	public function storingDataToGlobal(){ 
		$table = $this->TableContent->getTableOfUser($this->userlevel); 
		$datanav = $this->Content->getSideBarContent();
		$datakategori = $this->Content->getContentKategori($this->userlevel); 
		$this->view->getEnvironment()->addGlobal('data',[
			'datanav'=>$datanav,
			'datakategori'=>$datakategori, 
			'table'=>$table,
		]);
	}
	
}