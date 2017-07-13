<?php

namespace App\Controllers\Aflog;

use App\Controllers\Controller; 
use App\Controllers\Content\Content; 
use App\Controllers\Content\TableContent; 
use App\Models\Kategori; 
use Respect\Validation\Validator as v;

Class TableOfMap extends Controller
{
	protected $userlevel;
	public function getDataMap($request, $response){
		if($this->container->auth->check()){
			$this->userlevel = $this->container->auth->UserLevel();
			$this->Content->setSideBarContent(0);
			$datanav = $this->Content->getSideBarContent();
		}


		$this->storingDataToGlobal();
		return $this->view->render($response,'afterlogin/TableCubeMap.twig');
	}
	public function postNewKategori($request, $response){
		$validation = $this->validator->validate($request,[
			'namaKategori' => v::notEmpty()->KategoriNotAvailable(),
		]);
		if($validation->failed()){
			$this->flash->addMessage('error','nama kategori yang anda masukan sudah ada, silahkan input lagi');
			return $response->withRedirect($this->router->pathFor('user.dashboard'));
		}
		$kategori = Kategori::create([ 
				'nama_kategori'=> $request->getParam('namaKategori'), 
			]);
		return $response->withRedirect($this->router->pathFor('user.dashboard'));
	}
	public function deleteCubeMap($request, $response){ 
		$this->TableContent->deleteCubeMap($request->getParam('idcube'));
		return $response->withRedirect($this->router->pathFor('user.dashboard'));
	}

	public function storingDataToGlobal(){
		$kategori= $this->TableContent->getKategori(); 
		$table = $this->TableContent->getTableOfMap($this->userlevel); 
		$keterangan = $this->TableContent->keteranganCount($this->userlevel); 
		$datanav = $this->Content->getSideBarContent();
		$datakategori = $this->Content->getContentKategori($this->userlevel); 
		$this->view->getEnvironment()->addGlobal('data',[
			'datanav'=>$datanav,
			'datakategori'=>$datakategori,
			'kategori'=>$kategori,
			'table'=>$table,
			'keterangan'=>$keterangan,
		]);
	}
	
}