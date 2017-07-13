<?php 

namespace App\Validation\Rules;
use App\Models\User;
use Respect\Validation\Rules\AbstractRule;
/**
* create by divar jati
*/
class MatchesShadow extends AbstractRule
{
	protected $shadow;
	public function __construct($shadow){
		$this->shadow= $shadow;
	}
	public function validate($input){
		 return password_verify($input, $this->shadow);
	}
}