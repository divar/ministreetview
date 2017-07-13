<?php 

namespace App\validation; 

use Respect\Validator\validation as Respect;
use Respect\Validation\Exceptions\NestedValidationException;
class Validator  
{
	 protected $errors, $errors1;
	function validate($request, array $rules){
		foreach ($rules as $field => $rule) {
			try{
				$rule->setName($field)->assert($request->getParam($field));
			}catch(NestedValidationException $e){
				$this->errors[$field] = $e->getMessages();
			}
		}
		$_SESSION['errors']=$this->errors;
		return $this;
	}
	function imgvalidate($request, array $rules){
		foreach ($rules as $field => $rule) {
			try{ 
				 
				$rule->assert($request->getUploadedFiles()[$field]->getStream());

			}catch(NestedValidationException $e){
				$this->errors1[$field] = $e->getMessages();
			}
		}
		$_SESSION['errors1']=$this->errors1;
		return $this;
	}
	public function failed(){
		return !empty($this->errors);
	}
	public function imgfailed(){
		return !empty($this->errors1);
	}
}