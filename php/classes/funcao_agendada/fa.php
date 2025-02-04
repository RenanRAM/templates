<?php
//refazer o buffer para já pegar as linhas pois está quebrando os caracteras multibyte



//classe para manipular o agendamento de funções em arquivos .txt que estão ligados à instância da classe
//é possível ler as funções agendadas na memória (e seu parâmetro salvo em json)
class Funcao_Agendada{
	private const PREFIX = "fa_mem";
	private const SEPARADOR = "|><|";
	const TAMANHO_BUFFER = 8;
	private $arquivo = "";//caminho para o arquivo de memória desta instância

/*não funcionando
	private $bufLeitor = "";
	private $final = false;//true se não há nada mais para ser lido no arquivo
	private $offsetBufLeitura = 0;//final do buffer
	private $linhaInicialBuf = null;//linha inicial do buffer, se for null é desconhecida
	private $inicioQuebrado = false;
*/
	function __construct($mem){//cria ou abre instância de Funcao_Agendada, $mem é o identificador da memória desta instância
		$this->arquivo = __DIR__."/fa/".self::PREFIX.$mem.".txt";
		$dir = __DIR__."/fa/";
		if(!file_exists($dir)){
			//pasta não existe ainda, criar agora
			if(!mkdir($dir)){
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
/* não funcionando
	public function lerBuf($linha){//problemas com o buffer, provavelmente por causa de caracteres multibyte 
		$offset_inicio = 0;
		$numero_linha_atual = 0;
		if($this->bufLeitor === ""){
			if($this->final === true){
				//acabou o arquivo
				return false;
			}else{
				$this->encherBufferLeitura(0);
				if($this->bufLeitor === ""){
					//arquivo vazio
					return false;
				}
			}
		}
		if($this->linhaInicialBuf !== null){
			if((($linha === $this->linhaInicialBuf) && !$this->inicioQuebrado) || ($linha > $this->linhaInicialBuf)){
				//não resetar o buffer
				$offset_inicio = $this->offsetBufLeitura;
				$numero_linha_atual = $this->linhaInicialBuf;
			}
		}
		
		$linha_atual = "";
		$letra = "";
		do{
			$char = 0;
			$tamanho = strlen($this->bufLeitor);
			while($char < $tamanho){
				$letra = $this->bufLeitor[$char];
				if($letra !== "\n"){
					if($numero_linha_atual === $linha){
						$linha_atual .= $letra;
					}
				}else{
					$numero_linha_atual++;
					if($numero_linha_atual > $linha){
						break 2;
					}
				}
				$char++;
			}
			$offset_inicio = $this->offsetBufLeitura+self::TAMANHO_BUFFER+1;
			$this->encherBufferLeitura($offset_inicio);
			$this->linhaInicialBuf = $numero_linha_atual;
			if($letra === "\n"){
				$this->inicioQuebrado = false;
			}else{
				$this->inicioQuebrado = true;
			}
		}while(!$this->final);
		return $linha_atual;	
	}
*/
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

	private function encherBufferLeitura($offset = 0){
		echo "atualizando buffer <br>";
		$handle = fopen($this->arquivo, "r");
		fseek($handle,$offset);
		$this->bufLeitor = fread($handle, self::TAMANHO_BUFFER);
		$this->offsetBufLeitura = ftell($handle);
		$this->final = feof($handle);
		fclose($handle);
		if($offset === 0){
			$this->linhaInicialBuf = 0;
		}else{
			$this->linhaInicialBuf = null;
		}
		echo $this->bufLeitor."<br>";
		return;
	}
}


$teste = new Funcao_Agendada(2);

$i = 1;
$cont = 0;//contador de segurança
echo "<h3>O tamanho atual é ".$teste->tamanho()."</h3>";

/* não funcionando
echo "linha ".$teste->lerBuf(1);
echo "<br>";
echo "linha ".$teste->lerBuf(2);
echo "<br>";
echo "linha ".$teste->lerBuf(3);
echo "<br>";
echo "linha ".$teste->lerBuf(4);
echo "<br>";
*/


//todas as funções que retornarem true serão apagadas
while(($fn = $teste->ler($i)) !== false){
	//print_r($fn);
	//echo "<br/>";
	[$fnome,$fparam] = $fn;
	if($fnome($fparam)){//executa as funções, se o retorno for true apaga ela (pois deu certo), se for false pula para a próxima
		$teste->apagar($i);
		//não devemos fazer $i++ depois de apagar pois este método desloca todas as funções posteriores para traz, renovando a função agendada no offset $i
	}else{
		$i++;
	}
	$cont++;
	if($cont > 1000) break;
}


if(isset($_GET['agendar'])){
	$teste->agendar("nada",7);
}else if(isset($_GET['apagar'])){
	echo $teste->apagar($i-1);
}

function teste($n){
	return $n >= 10;
}


?>