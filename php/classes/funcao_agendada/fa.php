<?php

//classe para manipular o agendamento de funções em arquivos .txt que estão ligados à instância da classe
//é possível ler as funções agendadas na memória (e seu parâmetro salvo em json)
class Funcao_Agendada{
	private const PREFIX = "fa_mem";
	private const SEPARADOR = "|><|";
	private $arquivo = "";//caminho para o arquivo de memória desta instância

	function __construct($mem){//cria ou abre instância de Funcao_Agendada, $mem é o identificador da memória desta instância
		$this->arquivo = "fa/".self::PREFIX.$mem.".txt";
		if(!file_exists("fa/")){
			//pasta não existe ainda, criar agora
			if(!mkdir("fa/")){
				throw new Exception("Erro ao criar o diretório", 1);
			}
		}
		if(!file_exists($this->arquivo)){
			//criando um novo arquivo
			$novo_arquivo = fopen($this->arquivo, "w");
			if($novo_arquivo === false){
				throw new Exception("Erro ao criar o arquivo", 1);
			}
			//arquivo novo gerado a partir deste ponto
			fclose($novo_arquivo);
		}
	}

	public function agendar($func_nome,$parametro = null){//agenda o nome de função e um único parâmetro para ela
		$handle = fopen($this->arquivo, "r+");
		//ir para o final do arquivo
		fseek($handle, 0,SEEK_END);
		$status = fwrite($handle, $func_nome.self::SEPARADOR.json_encode($parametro)."\n");
		fclose($handle);
		return $status;
	}

	public function ler($offset){//retorna a função agendada no $offset e seu parametro desse jeito [func,param] ou false, $offset começa em 1
		//param pode ser null
		$handle = fopen($this->arquivo, "r");
		$cont = 0;
		$linha = "";
		while($cont < $offset){
			$linha = fgets($handle);
			if($linha === false) break;
			$cont++;
		}
		fclose($handle);
		if($linha === false || $linha === "") return false;
		$arr = explode(self::SEPARADOR, $linha);
		$func_nome = $arr[0];
		$param = $arr[1] !== ""?json_decode($arr[1]):null;
		return [$func_nome,$param];
	}

	public function apagar($offset){//apaga a função agendada no $offset e reduz em 1 o index de cada função depois do apagado, começa em 1
		$handle = fopen($this->arquivo, "r+");
		$cont = 0;
		$origem_pos = 0;
		while($cont < $offset){
			$cont++;
			$origem_pos = ftell($handle);
			$linha = fgets($handle);
			if($linha === false) break;
		}
		while(($linha = fgets($handle))!== false){//apaga e desfragmenta
			$atual_pos = ftell($handle);
			fseek($handle,$origem_pos);
			$tam = fwrite($handle, $linha);
			fseek($handle,$atual_pos);
			$origem_pos += $tam;
		}
		$status = ftruncate($handle,$origem_pos);
		fclose($handle);
		return $status;
	}

	public function reset(){//apaga toda memória da instância
		$handle = fopen($this->arquivo, "w");
		fclose($handle);
		return;
	}

	public function tamanho(){//retorna o total de funções agendadas
		$handle = fopen($this->arquivo, "r");
		$cont = 0;
		while(fgets($handle) !== false){
			$cont++;
		}
		fclose($handle);
		return $cont;
	}
}


$teste = new Funcao_Agendada(2);

$i = 1;
echo "<h3>O tamanho atual é ".$teste->tamanho()."</h3>";
while(($fn = $teste->ler($i)) !== false){
	print_r($fn);
	echo "<br/>";
	$i++;
}

if(isset($_GET['agendar'])){
	$teste->agendar("nada",7);
}else if(isset($_GET['apagar'])){
	echo $teste->apagar($i-1);
}


?>