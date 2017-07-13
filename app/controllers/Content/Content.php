<?php
namespace App\Controllers\Content;
use App\Models\User;
use App\Models\User_Permission;
use App\Models\CubeMap;
use App\Models\Keterangan;
use App\Models\Kategori;
class Content
{
	protected $cubemapdata, $cubemap, $side, $sidekategori;
	 public function setContent($userLevel, $idcube){
		$this->cubemap = CubeMap::where('user_level', '>=', $userLevel)->where('id_cube', $idcube)->first();
 		if(!is_null($this->cubemap)){
			$this->cubemap->map1 = base64_encode(file_get_contents("../map/raw/".$this->cubemap->map1));
			$this->cubemap->map2 = base64_encode(file_get_contents("../map/raw/".$this->cubemap->map2));
			$this->cubemap->map3 = base64_encode(file_get_contents("../map/raw/".$this->cubemap->map3));
			$this->cubemap->map4 = base64_encode(file_get_contents("../map/raw/".$this->cubemap->map4));
			$this->cubemap->map5 = base64_encode(file_get_contents("../map/raw/".$this->cubemap->map5));
			$this->cubemap->map6 = base64_encode(file_get_contents("../map/raw/".$this->cubemap->map6)); 
 	 	}
	 }
	 public function getContent(){
	 	return $this->cubemap;
	 }
	 public function getKeteranganContent($idcube){
	 	$keterangan =  json_encode(Keterangan::where('idcube',$idcube)->get());
	 	return $keterangan;
	  	 
	 }
	  
	 public function setSideBarContent($level_user){
		$this->side = CubeMap::select('id_kategori', 'id_cube', 'nama_tempat', 'deskripsi')->where('user_level','>=', $level_user)->get();
		$this->sidekategori = CubeMap::select('id_kategori', 'id_cube', 'nama_tempat', 'deskripsi')->where('user_level','>=', $level_user)->get();
	 }
	 public function getSideBarContent(){
	 	return $this->side;
	 }
	 public function getContentKategori($level_user){
	 	$s = CubeMap::join('kategori','CubeMap.id_kategori','=','kategori.id_kategori')->select('kategori.*')->where('user_level','>=',$level_user)->groupBy('CubeMap.id_kategori')->get();
	 	return $s;
	 }
}