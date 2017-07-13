<?php

namespace App\Controllers;
use Slim\Views\Twig as View; 
use App\Controllers\Content\Content; 
Class HomeController extends Controller
{ 
	protected $userlevel, $idcube;
	public function getTheCubeMap($request, $response){
		$this->idcube = $this->request->getParam('idcube');
		$this->userlevel = null;
		if($this->container->auth->check()){
			$this->userlevel = $this->container->auth->UserLevel();
		}else{
			$this->userlevel = 3;
		}
		$this->Content->setSideBarContent($this->userlevel);
		if(is_null($this->idcube)){ 
			if(sizeof($this->Content->getSideBarContent())>0){
				$this->idcube = $this->Content->getSideBarContent()[0]->id_cube; 
			} 
		}
		$this->Content->setContent($this->userlevel, $this->idcube); 
		if($this->userlevel>2){
			$this->Content->setContent(3, $this->idcube);
			$this->Content->setSideBarContent(3);
		}
		
		$this->storingDataToGlobal(); 
		return $this->view->render($response,'home.twig'); 
	}

	public function storingDataToGlobal(){
		$datamap = $this->Content->getContent(); 
		if(!is_null($datamap)){
			$keterangan = $this->Content->getKeteranganContent($this->idcube); 	
			$datanav = $this->Content->getSideBarContent();
			$datakategori = $this->Content->getContentKategori($this->userlevel); 
			$this->view->getEnvironment()->addGlobal('data',[
				'cubemap'=>$datamap,
				'datanav'=>$datanav,
				'datakategori'=>$datakategori,
				'keterangan' => $keterangan,
				]);	

		} 
		if(!isset($datamap)){
			$this->container->flash->addMessageNow('error',"Maaf Content ini tidak ada atau hanya bisa diakses oleh Admin"); 
		}
	}
}