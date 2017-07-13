<?php

namespace App\Controllers\Aflog;

use App\Controllers\Controller; 
use App\Controllers\Content\Content; 
use App\Controllers\Content\TableContent; 

Class TableOfKategori extends Controller
{
	protected $userlevel;
	public function getKategori($request, $response){
		if($this->container->auth->check()){
			$this->userlevel = $this->container->auth->UserLevel();
			if($this->userlevel>1){
				return $response->withRedirect($this->router->pathFor('user.dashboard'));	
			}
			$this->Content->setSideBarContent(0);
			$datanav = $this->Content->getSideBarContent();
		}
		$this->storingDataToGlobal();
		return $this->view->render($response,'afterlogin/TableKategori.twig');
	}
	public function updateKategori($request, $response){
		$update = $this->TableContent->updateKategori($request->getParam('id_kategori'),$request->getParam('nama_kategori'));
		if ($update==true) {
			return $response->withRedirect($this->router->pathFor('admin.kategori'));
		} 
	} 
	public function storingDataToGlobal(){ 
		$table = $this->TableContent->getKategori(); 
		$datanav = $this->Content->getSideBarContent();
		$datakategori = $this->Content->getContentKategori($this->userlevel); 
		$this->view->getEnvironment()->addGlobal('data',[
			'datanav'=>$datanav,
			'datakategori'=>$datakategori, 
			'table'=>$table,
		]);
	}
	
}