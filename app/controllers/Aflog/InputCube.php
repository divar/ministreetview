<?php

namespace App\Controllers\Aflog;

use App\Controllers\Controller; 
use App\Controllers\Content\Content; 
use App\Models\User_Permission; 
use App\Models\CubeMap; 
use App\Controllers\Content\TableContent; 
use Respect\Validation\Validator as v;
use Upload\Storage\FileSystem;
Class InputCube extends Controller
{
	protected $userlevel, $errors;
	 protected $file = [], $filedata = [];
	
	public function getInputCube($request, $response){
		$kategori= $this->TableContent->getKategori();
		if(sizeof($kategori)<1){
			$this->flash->addMessage('error','Kategori masih Kosong, Silahkan tambahkan Kategori terlebih dahulu');
			return $response->withRedirect($this->router->pathFor('user.dashboard'));
		}
		$this->view->getEnvironment()->addGlobal('data',[
			  'kategori'=>$kategori,
		]);
		return $this->view->render($response,'afterlogin/forminput/InputCube.twig');
	}
	public function postinputcube($request, $response){ 
 
		$validation = $this->validator->validate($request,[
			'nama_lokasi'=> v::notEmpty(),
			'deskripsi' => v::notEmpty(),
			'kategori' => v::notEmpty()->KategoriAvailable(),
			'userlevel' => v::intVal(), 
		]);
		if($validation->failed()){
			$this->flash->addMessage('error','file yang di inputkan tidak valid'.$this->errors);
			return $response->withRedirect($this->router->pathFor('form.inputcube'));
		}
		$move = $this->movefile(); 
		if ($move) {
			$this->flash->addMessage('info','anda telah menambahkan 1 lokasi');
			$cube = cubemap::create([ 
				'id_kategori'=> $request->getParam('kategori'),
				'nama_tempat'=> $request->getParam('nama_lokasi'),
				'deskripsi'=> $request->getParam('deskripsi'),
				'user_level'=> $request->getParam('userlevel'),
				'map1'=> $this->filedata[0]['name'],
				'map2'=> $this->filedata[1]['name'],
				'map3'=> $this->filedata[2]['name'],
				'map4'=> $this->filedata[3]['name'],
				'map5'=> $this->filedata[4]['name'],
				'map6'=> $this->filedata[5]['name'],
			]); 
			return $response->withRedirect($this->router->pathFor('user.dashboard'));
		}else{
			$this->container->view->getEnvironment()->addGlobal('errors',$this->errors);
			$this->flash->addMessage('error','file yang di inputkan tidak sesuai, maksimal ukuran gambar 5 MB dan ratio gambar 1:1'.$this->errors);
			return $response->withRedirect($this->router->pathFor('form.inputcube'));
		} 
	}	
	public function movefile(){ 
		$storage = new FileSystem(INC_ROOT.'/map/raw');

		$erormap=true; 
		for($i=0;$i<6;$i++){
			$map='map'.($i+1);
			$this->file[$i] = new \Upload\File($map, $storage);

			$new_filename = 'time_'.time().'_'.uniqid().'_'.$map;
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
			array_push($this->filedata, $data);
			if(!$this->file[$i]->validate()){
				$erormap = false; 
			}
			if($i==5 && $erormap != false){
				for ($o=0; $o < 6; $o++) { 
					try { 
					   $this->file[$o]->upload();					    
					} catch (\Exception $e) {
						 $this->errors = $this->file[$o]->getErrors();  
						return false;

					}
				}
				return true;
			}
		}
		  
	}

}