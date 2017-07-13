<?php

namespace App\Controllers\Aflog;

use App\Controllers\Controller; 
use App\Controllers\Content\Content; 
use App\Controllers\Content\TableContent; 
use App\Models\Kategori; 
use Respect\Validation\Validator as v;

Class TableOfKeteranganMap extends Controller
{
	protected $userlevel, $idcube, $status;
	public function getDataKeterangan($request, $response){
		if($this->container->auth->check()){
			$this->userlevel = $this->container->auth->UserLevel();
			$this->Content->setSideBarContent(0);
			$datanav = $this->Content->getSideBarContent();
		}
		$this->idcube = $request->getParam('idcube');
		$this->storingDataToGlobal();
		if($this->status < 1){
			return $response->withRedirect($this->router->pathFor('user.dashboard'));
		}else{
			return $this->view->render($response,'afterlogin/tableKeterangan.twig');
		} 
	}
	public function deleteKeterangan($request, $response){ 
		$this->TableContent->deleteKeterangan($request->getParam('id_ket'));
		return $response->withHeader('Location', 'http://ministreetview.tkl/admin/TableKeterangan?idcube='.$request->getParam('idcube'));
	}
	public function postDataKeterangan($request, $response){ 
		 $this->TableContent->updateKeterangan($request->getParam('id_ket'),$request->getParam('judul'),$request->getParam('deskripsi'));
		 return $response->withHeader('Location', 'http://ministreetview.tkl/admin/TableKeterangan?idcube='.$request->getParam('idcube'));
	}
	
	public function storingDataToGlobal(){
		$kategori= $this->TableContent->getKategori(); 
		$authcheck = $this->TableContent->authCheck($this->userlevel, $this->idcube);
		if($authcheck == 1 ){
			$table = $this->TableContent->getTableOfKeterangan($this->idcube);
			echo sizeof($table); 
			if(sizeof($table)<=0){ 
				$this->status = 0;
			}else{
				$this->status = 1;
			}
		} 
		$datanav = $this->Content->getSideBarContent();
		$datakategori = $this->Content->getContentKategori($this->userlevel); 
		$this->view->getEnvironment()->addGlobal('data',[
			'datanav'=>$datanav,
			'datakategori'=>$datakategori,
			'kategori'=>$kategori,
			'table'=>$table,
			'idcube'=>$this->idcube,
		]);
	}
	
}