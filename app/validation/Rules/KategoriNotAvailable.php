<?php 

namespace App\Validation\Rules;
use App\Models\Kategori;
use Respect\Validation\Rules\AbstractRule;
/**
* create by divar jati
*/
class KategoriNotAvailable extends AbstractRule
{
	public function validate($input){
		return kategori::where('nama_kategori',$input)->count()===0;
	}
}