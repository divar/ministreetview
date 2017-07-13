<?php
namespace App\Middleware; 
use Slim\Views\Twig as View;
class ReadImage{
	public function readImage($request,$response){ 
	   	readfile("../map/raw/".$request->getParam('img'));

		return $this->container->view->render($response,'read.twig');
	}
}
  
?>