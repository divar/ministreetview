<?php

namespace App\Controllers\Aflog;

use App\Controllers\Controller; 
use App\Controllers\Content\Content; 
use App\Models\User_Permission; 
use App\Models\CubeMap; 
use App\Controllers\Content\TableContent; 
use Respect\Validation\Validator as v;
use Upload\Storage\FileSystem;
Class UpdateCubeMap extends Controller
{
	protected $userlevel, $errors;
	 protected $file = [], $filedata = [];
	
	public function getInputCube($request, $response){
		$cubemap = CubeMap::where('id_cube', $request->getParam('idcube'))->get(); 
		 
		$kategori= $this->TableContent->getKategori();
		if(sizeof($kategori)<1){
			$this->flash->addMessage('error','Kategori masih Kosong, Silahkan tambahkan Kategori terlebih dahulu');
			return $response->withRedirect($this->router->pathFor('user.dashboard'));
		}
		$this->view->getEnvironment()->addGlobal('data',[
			  'kategori'=>$kategori,
			  'idcube'=>$request->getParam('idcube'),
			  'cubemap'=>$cubemap,
		]);
		 
		return $this->view->render($response,'afterlogin/forminput/updateCube.twig');
	}
	// public function storingDataToGlobal(){
	// 	$dataCubeMap = CubeMap::where('id_cube','10')->get();
	// 	if(!is_null($this->dataCubeMap)){
	// 		$dataCubeMap->map1 = base64_encode(file_get_contents("../map/raw/".$dataCubeMap->map1));
	// 		$dataCubeMap->map2 = base64_encode(file_get_contents("../map/raw/".$dataCubeMap->map2));
	// 		$dataCubeMap->map3 = base64_encode(file_get_contents("../map/raw/".$dataCubeMap->map3));
	// 		$dataCubeMap->map4 = base64_encode(file_get_contents("../map/raw/".$dataCubeMap->map4));
	// 		$dataCubeMap->map5 = base64_encode(file_get_contents("../map/raw/".$dataCubeMap->map5));
	// 		$dataCubeMap->map6 = base64_encode(file_get_contents("../map/raw/".$dataCubeMap->map6));
	// 	}
	// 	$this->view->getEnvironment()->addGlobal('data',[
	// 		'dataCubeMap'=>$dataCubeMap
	// 	]);
	// }
	public function postInputcube($request, $response){
		$validation = $this->validator->validate($request,[
			'nama_lokasi'=> v::notEmpty(),
			'deskripsi' => v::notEmpty(),
			'kategori' => v::notEmpty()->KategoriAvailable(),
			'userlevel' => v::intVal(), 
		]);
		if($validation->failed()){
			$this->flash->addMessage('error','file yang di inputkan tidak valid'.$this->errors);
			return $response->withRedirect($this->router->pathFor('form.updatecube'));
		} 
		$move = $this->movefile($request->getParam('idcube'));
		// if ($move) {
		// 	$this->flash->addMessage('info','anda telah mengupdate lokasi');
			$cube = cubemap::where('id_cube',$request->getParam('idcube'))->update(array(
				'id_kategori'=> $request->getParam('kategori'),
				'nama_tempat'=> $request->getParam('nama_lokasi'),
				'deskripsi'=> $request->getParam('deskripsi'),
				'user_level'=> $request->getParam('userlevel'),)); 
		  	return $response->withRedirect($this->router->pathFor('user.dashboard'));
		// }else{
		// 	$this->container->view->getEnvironment()->addGlobal('errors',$this->errors);
		// 	$this->flash->addMessage('error','file yang di inputkan tidak sesuai, maksimal ukuran gambar 5 MB dan ratio gambar 1:1'.$this->errors);
		// 	return $response->withRedirect($this->router->pathFor('form.inputcube'));
		// } 
	}	
	public function movefile($idcube){
		$storage = new FileSystem(INC_ROOT.'/map/raw');
		$yangterupdate = [];
		$erormap=true;  
		for($i=0;$i<6;$i++){
			$map='map'.($i+1);
			if (addslashes($_FILES[$map]['size'])>0) {
				$po = CubeMap::select($map)->where('id_cube', $idcube)->get(); 
				array_push($yangterupdate, $i);
				unlink ( INC_ROOT.'/map/raw/'.$po[0][$map] );
				$this->file[$i] = new \Upload\File($map, $storage);

				$new_filename = $po[0][$map];
				$this->file[$i]->setName($new_filename);

				$this->file[$i]->addValidations(array(
				    new \Upload\Validation\Mimetype(array('image/jpeg','image/png')), 
				    new \Upload\Validation\Size('6M')
				));
		 		 
				$data = array(
				    'name'       => $this->file[$i]->getNameWithExtension(),
				    'extension'  => $this->file[$i]->getExtension(),
				    'mime'       => $this->file[$i]->getMimetype(),
				    'size'       => $this->file[$i]->getSize(),
				    'md5'        => $this->file[$i]->getMd5(),
				    'dimensions' => $this->file[$i]->getDimensions()
				);
				// array_push($this->filedata, $data);
				if(!$this->file[$i]->validate()){
					$erormap = false;
				}
				echo $erormap.$i;
			 }
				if($i==5 && $erormap != false){
					echo "id for";
					for ($o=0; $o < 6; $o++) {
					echo "for 2"; 
						if (in_array($o, $yangterupdate)) {
							echo "copied"; 
							try { 
							   $this->file[$o]->upload();					    
							} catch (\Exception $e) {
								 $this->errors = $this->file[$o]->getErrors();  
								return false;
							}
						}
					}
					return true;
				}
			 
		}
		echo 'asadsda';
	 
	}

}