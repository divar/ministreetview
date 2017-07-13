<?php
namespace App\Controllers\Content;

use App\Models\User;
use App\Models\User_Permission;
use App\Models\CubeMap;
use App\Models\Keterangan;
use App\Models\Kategori;
Class TableContent
{
	public function getKategori(){
		return kategori::All();
	}
	public function getTableOfMap($level_user){
		return CubeMap::select('id_kategori','id_cube', 'nama_tempat',  'deskripsi')->where('user_level','>=', $level_user)->get();
	}
	public function authCheck($level_user, $idcube){
		return CubeMap::select('id_kategori')->where('user_level','>=', $level_user)->where('id_cube',$idcube)->count();
	}
	public function getTableOfUser(){
		return User::join('userpermission','users.id','=','userpermission.user_id')->select('users.id','username', 'email',  'user_level')->get();
	}
	public function getTableOfKeterangan($idcube){
		return Keterangan::select('id_ket','judul', 'deskripsi')->where('idcube',$idcube)->get();
	}
	public function keteranganCount($user_level){
		return Keterangan::join('CubeMap','keterangan.idcube','=','CubeMap.id_cube')->select('idcube')->where('CubeMap.user_level','>=',$user_level )->groupBy('idcube')->get();
		
	}
	public function deleteKeterangan($id_ket){
		
		if(Keterangan::where('id_ket', $id_ket)->delete()){
			return true; 
		}
		return false;
	}
	public function deleteCubeMap($id_cube){
		$map = CubeMap::select('map1','map2','map3','map4','map5','map6')->where('id_cube','>=', $id_cube)->get(); 

		foreach ($map as $k => $v) {
			for ($i=0; $i < 7; $i++) { 
				unlink ( INC_ROOT.'/map/raw/'.$v['map'.$i] );
			} 
		} 
		if(CubeMap::where('id_cube', $id_cube)->delete()){
			return true; 
		}
		return false;
	}
	public function updateUserLevel($userId,$userlevel){
		return User_Permission::where('user_id',$userId)->update(array('user_level'=>$userlevel));
	}
	public function updateKategori($id_kategori,$nama_kategori){
		return Kategori::where('id_kategori',$id_kategori)->update(array('nama_kategori'=>$nama_kategori));
	}
	public function deleteUser($id){
		
		if(User::where('id', $id)->delete()){
			User_Permission::where('user_id', $id)->delete();
			return true; 
		}
		return false;
	}
	public function updateKeterangan($id_ket,$judul,$deskripsi){
		return Keterangan::where('id_ket',$id_ket)->update(array('judul'=>$judul, 'deskripsi'=> $deskripsi));
	}

}