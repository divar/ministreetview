<?php

namespace App\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;
/**
* 
create by divar jati
*/
class KategoriNotAvailableException extends ValidationException
{ 
	public static $defaultTemplates = [
		self::MODE_DEFAULT=>[
			self::STANDARD => 'Kategori Sudah Ada',
		],
	];
}