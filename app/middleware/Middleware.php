<?php 

namespace App\Middleware;
/**
* 
created by divar
*/
class Middleware 
{
	protected $container;

	public function __construct($container)
	{
		$this->container = $container;
	}
} 