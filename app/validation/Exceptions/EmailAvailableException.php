<?php

namespace App\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;
/**
* 
create by divar jati
*/
class EmailAvailableException extends ValidationException
{ 
	public static $defaultTemplates = [
		self::MODE_DEFAULT=>[
			self::STANDARD => 'Email sudah ada',
		],
	];
}