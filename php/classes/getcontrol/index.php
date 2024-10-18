<?php
class getControl{// V1 controla as rotas para a pasta páginas

	private static $getControl_status = 0;
	private $getControl_padrao = 'paginas/index.php';
	private static $getControl_ark = '';
	private $getControl_diretorio = 'paginas/';

	public function __construct($rotaPadrao = '', $dir = ''){
		if(self::$getControl_status != 1){
			if($rotaPadrao != ''){
				$this->getControl_padrao = $rotaPadrao;
			}
			self::$getControl_ark = $this->getControl_padrao;
		}
		if($dir != ''){
			$this->getControl_diretorio = $dir;
		}
	}

	public function __get($nome){
		$novoDir = $this->getControl_diretorio.$nome.'/';
		$obj = new getControl('',$novoDir);
		return $obj; 
	}

	public function rota($getc,$arquivo,$getvalor = null){
		$valor = 0;
		if(self::$getControl_status != 1){
			if(($getc == '' || $getc == 0) && $_SERVER['QUERY_STRING'] == ''){
				self::$getControl_status = 1;
				self::$getControl_ark = $this->getControl_diretorio.$arquivo;
				return true;
			}else if(isset($_GET[$getc])){
				$valor = $_GET[$getc];
				if($getvalor != null){
					if($getvalor == $valor){
						self::$getControl_ark = $this->getControl_diretorio.$arquivo;
						self::$getControl_status = 1;
						return true;
					}
				}else{
					self::$getControl_ark = $this->getControl_diretorio.$arquivo;
					self::$getControl_status = 1;
					return true;
				}		
			}
		}
		return false;
	}

	public function ver(){
		return self::$getControl_ark;
	}
}


	

/*Exemplo
	$controle = new getControl('paginas/pagina1.php');

	
	$controle->rota('nada','pagina1.php');
	$controle->header->rota('galinha','index2.php');
	$controle->header->conteudo->rota('conteudo','outro.php');

	

	include($controle->ver());
*/
?>

<?php
final class getControl2{// V2 controla as rotas para a pasta páginas

	private const getControl_paginaErro = 'paginas/index.php';//usar um constante
	public $getControl_status = 0;
	private $getControl_padrao = 'paginas/index.php';//arquivo padrão caso não encontre rotas
	private $getControl_ark = '';
	private $getControl_diretorio = '';
	private const getControl_reset_dir = 'paginas/';//usar uma constatnte
	private $getControl_ant = null;//controlador anterior

	public function __construct($rotaPadrao = '', $dir = '',$anterior=null){
		if($anterior instanceof getControl2){
			$this->getControl_diretorio=$dir;
			$this->getControl_ant = $anterior;
		}else{
			$this->getControl_diretorio = self::getControl_reset_dir;
		}
		
		if($this->getControl_status != 1){
			if($rotaPadrao != ''){
				$this->getControl_padrao = $rotaPadrao;
			}
			$this->getControl_ark = $this->getControl_padrao;
		}
	}

	public function __get($nome){
		$novoDir = $this->getControl_diretorio.$nome.'/';
		$obj = new getControl2('',$novoDir,$this);
		return $obj; 
	}

	public function rota($getc,$arquivo,$getvalor = null){
		$valor = 0;
		if($this->getStatus() != 1){
			if(($getc == '' || $getc == 0) && $_SERVER['QUERY_STRING'] == ''){
				$this->setArk($this->getControl_diretorio.$arquivo);
				$this->setStatus(1);
				return true;
			}else if(isset($_GET[$getc])){
				$valor = $_GET[$getc];
				if($getvalor != null){
					if($getvalor == $valor){
						$this->setArk($this->getControl_diretorio.$arquivo);
						$this->setStatus(1);
						return true;
					}
				}else{
					$this->setArk($this->getControl_diretorio.$arquivo);
					$this->setStatus(1);
					return true;
				}		
			}
		}
		return false;
	}

	private function getStatus(){//pega o status da origem
		if($this->getControl_ant instanceof getControl2){
			return $this->getControl_ant->getStatus();
		}else{
			return $this->getControl_status;
		}
	}

	private function setStatus($status){//seta o status da origem
		if($this->getControl_ant instanceof getControl2){
			$this->getControl_ant->setStatus($status);
		}else{
			$this->getControl_status = $status;
		}
	}

	private function setArk($ark){//vai setando o arquivo até chegar na origem
		if($this->getControl_ant instanceof getControl2){ 
			$this->getControl_ant->setArk($ark);
		}else{
			$this->getControl_ark=$ark;
		}	 
	}

	public function ver(){
		if(file_exists($this->getControl_ark))
			return $this->getControl_ark;
		return self::getControl_paginaErro;
	}
}


	


	$controle = new getControl2('paginas/pagina1.php');

	
	//$controle->rota('nada','pagina1.php');
	$controle->header->rota(0,'index2.php');// 0 é para setar como rota padrão
	$controle->rota('outro','pagina1.php','sete');
	$controle->header->conteudo->rota('outro','outro.php');

	//$controle->header->conteudo->rota('conteudo','outro.php');

	
	echo "ver: ".$controle->ver();
?>