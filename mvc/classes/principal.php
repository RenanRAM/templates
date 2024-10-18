<?php
	class MySql{

		private static $pdo;

		public static function conectar(){
			if(self::$pdo == null){
				try{
					self::$pdo = new PDO('mysql:host='.HOST.';dbname='.DATABASE,USER,PASSWORD,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
					self::$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
				}catch(Exception $e){
					echo '<h2>Erro ao conectar no banco de dados SQL</h2>';
				}
			}

			return self::$pdo;
		}
	}


	trait verificaPadrao{
		public function avaliar($urlReal,$padrao){

			$url = str_ends_with($urlReal,'/')?substr($urlReal,0,-1):$urlReal;//remover o / do final da url caso exista

			if(substr_count($url, '/') != substr_count($padrao,'/'))
				return false;

			$tamurl = strlen($url);
			$tampadrao = strlen($padrao);
			$aux = 0;//contador auxiliar

			for($i = 0;$i<$tamurl;$i++){
				if($aux>=$tampadrao)//padrão acabou antes da url
					return false;
				if($padrao[$aux] !== '?'){
					if($padrao[$aux] !== $url[$i]){
						return false;//padrão não é ? e é diferente da url
					}else{
						$aux++;
					}
				}else{
					if($url[$i] === '/'){
						$aux+=2;
					}
				}
			}
			return !(!str_ends_with($padrao,'?') && ($aux<$tampadrao));//caso a url acabe antes do padrão, sendo que deu match até o fim da url
		}
	}

	final class roteadorURL{

		use verificaPadrao {avaliar as private;}
		
		private $url = '';
		private $rotaEncontrada = false;
		private const PAGINA_ERRO = 'erro404.php';//criar uma página de erro para caso não tenha uma rota

		function __construct($url){
			$this->url = $url;
		}
/*
		private function avaliar($padrao){//o padrão está funcionando, talvez adicionar um outro caracter que representa qualquer coisa ou infinitas / depois...
													//exemplo pg1/alguma/* para dar match em qualquer coisa depois de pg1/alguma
			$url = str_ends_with($this->url,'/')?substr($this->url,0,-1):$this->url;//remover o / do final da url caso exista

			if(substr_count($url, '/') != substr_count($padrao,'/'))
				return false;

			$tamurl = strlen($url);
			$tampadrao = strlen($padrao);
			$aux = 0;//contador auxiliar

			for($i = 0;$i<$tamurl;$i++){
				if($aux>=$tampadrao)//padrão acabou antes da url
					return false;
				if($padrao[$aux] !== '?'){
					if($padrao[$aux] !== $url[$i]){
						return false;//padrão não é ? e é diferente da url
					}else{
						$aux++;
					}
				}else{
					if($url[$i] === '/'){
						$aux+=2;
					}
				}
			}
			return !(!str_ends_with($padrao,'?') && ($aux<$tampadrao));//caso a url acabe antes do padrão, sendo que deu match até o fim da url
		}
*/

		public function rota($padrao,$func,$parametros = 'null'){
			if($this->rotaEncontrada){
				return false;
			}else{
				if($this->avaliar($this->url,$padrao)){//avaliando o padrão e a url
					$this->rotaEncontrada = true;
					$valores = explode('/',$padrao);
					$valoresURL = explode('/', $this->url);
					$indices = [];
					array_walk($valores, function($val,$cha) use (&$indices){
						if($val === '?'){
							$indices[] = $cha;
						}
					});
					$contp=1;
					foreach ($indices as $value) {
						$textoV ='p'.$contp;
						global $$textoV;
						$$textoV = $valoresURL[$value];
						$contp++;
					}
					if($parametros !== 'null'){
						$func($parametros);
					}else{
						$func();
					}
					return true;
				}else{
					return false;
				}
			}
		}

		public function forcarCarregamento(){//funão para ser executada após testar todas as rotas, se nenhuma rota fou encontrada ela carrega a página de erro
			if(!$this->rotaEncontrada){
				include(self::PAGINA_ERRO);
			}
			return false;	
		}


	}

?>