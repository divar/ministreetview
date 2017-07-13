<?php 

namespace App\Validation\Rules;
use App\Models\Kategori;
use Respect\Validation\Rules\AbstractRule;
/**
* create by divar jati
*/
class KategoriAvailable extends AbstractRule
{
	public function validate($input){
		return kategori::where('id_kategori',$input)->count()===1;
	}
}