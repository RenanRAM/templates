<?php
	
	//carrega todos os models
	$dirs = new DirectoryIterator('models');
	$dirs->rewind();
	while($dirs->valid()){
		if($dirs->getExtension() == 'php'){
			include($dirs->getPathname());
		}
		$dirs->next();
	}

	class controlador1{
	
		function __construct(){
			
		}

		public function index(){
			$m1 = new modelo1();
			$m1->index();
		}

		public function palavra($caxi = ''){
			$m2 = new modelo1();
			$m2->carregaPalavra($caxi);
		}
	}
?>