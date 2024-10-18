<?php
//este arquivo foi carregado no mesmo dirtetório do config.php
	class modelo1{
		
		function __construct(){
			
		}

		public function index(){
			$this->carregarView("pagina1.php",
				["titulo"=>"Bem vindo",
				"meta"=>METATAGS,
				"dia"=>date("d"),
				"css"=>"<link rel='stylesheet' type='text/css' href='".PATH."/css/estilo1.css'>"]);
		}

		public function carregaPalavra($palavra = ''){
			$this->carregarView("pagina1.php",
				["titulo"=>$palavra,
				"meta"=>METATAGS,
				"dia"=>date("d"),
				"css"=>"<link rel='stylesheet' type='text/css' href='".PATH."/css/estilo1.css'>"]);
		}

		private function carregarView($view, $parametros=[]){
			if(file_exists("views/".$view)){
				extract($parametros,EXTR_PREFIX_SAME,"param_");
				include("views/".$view);
				return true;
			}else{
				return false;
			}
		}
	}
?>