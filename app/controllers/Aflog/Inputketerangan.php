<?php

namespace App\Controllers\Aflog;

use App\Controllers\Controller; 
use App\Controllers\Content\Content; 
use App\Models\User_Permission; 
use App\Models\CubeMap; 
use App\Models\Keterangan; 
use App\Controllers\Content\TableContent; 
use Respect\Validation\Validator as v;
use Upload\Storage\FileSystem;
Class InputKeterangan extends Controller
{
	protected $userlevel, $idcube;
	public function getinputketerangan($request, $response){ 
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
		return $this->view->render($response,'afterlogin/forminput/InputTambahKeterangan.twig');
	}
	public function postinputketerangan($request, $response){
		$keterangancube = $request->getParam('activitiesArray');
		$idcube = $request->getParam('id');
		for($i=0;$i<sizeof($keterangancube);$i++){
			Keterangan::create([
				'id_ket'=>'',
				'idcube'=>$idcube,
				'judul'=>$keterangancube[$i][1],
				'deskripsi'=>$keterangancube[$i][2],
				'posx'=>$keterangancube[$i][3],
				'posy'=>$keterangancube[$i][4],
				'posz'=>$keterangancube[$i][5],
				
				]);
		}
		return var_dump($keterangancube);
	}
	public function storingDataToGlobal(){
		$datamap = $this->Content->getContent(); 
		if(!is_null($datamap)){ 
			$this->view->getEnvironment()->addGlobal('data',[
				'cubemap'=>$datamap, 
				]);	

		} 
		if(!isset($datamap)){
			$this->container->flash->addMessageNow('error',"Maaf Content ini tidak ada atau hanya bisa diakses oleh Admin"); 
		}
	}	

}