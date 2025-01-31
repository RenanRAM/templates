<?php
	/*
		Classes para trabalhar com banco de dados MySql

		Classe MySql: serve para executar qualquer comando no banco de dados, gera um objeto PDOStatement ao chamar o método conectar

		Classe base_bd: fornece funções úteis para qualquer classe que a implementa, a classe que implementa deve conter as constantes não privadas: TABELA e COLUNAS_TIPOS. Veja o exemplo abaixo na classe teste1


	*/


	//é preciso definir as constantes para as classes funcionarem
	define('HOST','localhost');
	define('USER','root');
	define('PASSWORD','');
	define('DATABASE','bd_teste');

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


	abstract class base_bd{

		private $colunas = [];
		private $tipos = [];

		public function __construct(){
			if(!defined("static::TABELA") || !defined("static::COLUNAS_TIPOS")){
				throw new Exception("Constantes protected TABELA ou COLUNAS_TIPOS não foram definidas!");
			}
			$this->colunas = array_column(static::COLUNAS_TIPOS, 0);
			$this->tipos = array_column(static::COLUNAS_TIPOS, 1);
		}

		public function colunas(){
			return $this->colunas;
		}

		public function tipos(){
			return $this->tipos;
		}

		public function inserir_rapido($dados): bool{//insere uma linha, retorna true se deu certo, false em erro
			//$dados deve ser uma array lista com os dados na mesma ordem de $colunas, use as funções colunas() e tipos() para criar os arrays de dados
			$place = substr(str_repeat("?,", count($this->tipos)),0,-1);
			$query = "INSERT INTO `".static::TABELA."` (".implode(",", $this->colunas).") VALUES (".$place.")";
			$sql = MySql::conectar();
			$stm = $sql->prepare($query);
			self::executaBindSql($stm,$dados,$this->tipos);
			$status = $stm->execute();
			$stm->closeCursor();
			return $status;
		}

		public static function executaBindSql($stm,$valores,$tipos): bool{//executa bindValue() para cada valor e tipo correspondente (na mesma ordem): tipo 1 = inteiro, 2 = string
			if(!(array_is_list($valores) && array_is_list($tipos) && ($stm instanceof PDOStatement)))return false;
			$i = 1;
			foreach ($valores as $key => $valor) {
				$pdoT = PDO::PARAM_STR;
				if($tipos[$key] === 1){
					$pdoT = PDO::PARAM_INT;
				}
				$stm->bindValue($i,$valor,$pdoT);
				$i++;
			}
			return true;
		}
	}





	class teste1 extends base_bd{
		public const TABELA = "contatos";//nome da tabela no banco de dados

		// 1 = inteiro  2 = string
		protected const COLUNAS_TIPOS = [//colunas e tipos, não incluir id ou qualquer outro auto incremento
			["nome",2],
			["email",2],
			["idade",1],
			["peso",2],
			["plano",2],
			["pagamento",2],
		];
	}

	//Funcionamento:
	$c1 = new teste1();
	//print_r($c1->colunas()); obtendo as colunas
	//$c1->inserir_rapido(["Renan","teste@hotmail.com",18,"70","Básico","Parcelado"]); inserindo uma linha
	//base_bd::executaBindSql(null,[],[]); podemos chamar a função separadamente
?>