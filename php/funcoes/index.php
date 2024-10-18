<?php
	//função que verifica intervalo de tempo atual:
	//esta função verifica se a hora que ela foi chamada está dentro de um intervalo de tempo diário definido anteriormente
	//retorna true caso esteja dentro do intervalo, caso contrário retorna false
	
	//necessita destes defines:
	define('HORA_PERMITIDA','22:00');//formato hh:mm exemplo: 13:47
	define('TEMPO_PERMITIDO_MAX','4');//em horas formato int exemplo: 2, intervalo permitido [1,24]
	date_default_timezone_set('America/Sao_Paulo');
	//função:
	function verificarHora(){//verifica se está nos limites da hora permitida
		$dataAtual = new DateTime();
		$dataP = DateTime::createFromFormat('H:i',HORA_PERMITIDA);
		$dif = $dataAtual->diff($dataP);
		//$doze = new DateInterval("PT12H");
		$difV = ($dif->h*60*60)+($dif->i*60)+($dif->s);
		//$dozeV = ($doze->h*60*60)+($doze->i*60)+($doze->s);
		if($dif->invert == 0){
			$difV -= 24*60*60;
		}
		if($dif->invert == 1 || $difV < 0){
			//está dentro da hora permitida, verificar se o acesso ainda pode ser feito
			$interv = new DateInterval("PT".TEMPO_PERMITIDO_MAX."H");
			$intervV = ($interv->h*60*60)+($interv->i*60)+($interv->s);
			if(abs($difV)<=$intervV){
				return true;
			}
		}
		return false;
	}
	//exemplo:
	//echo 'Estamos dentro do intervalo? '.(verificarHora()?"Sim":"Não");
?>

<?php
	//retorna o ip do cliente
	function getIP(){
		if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ipDoCliente = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}elseif(!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ipDoCliente = $_SERVER['HTTP_CLIENT_IP'];
		}else{
			$ipDoCliente = $_SERVER['REMOTE_ADDR'];
		}
		return $ipDoCliente;
	}
?>

<?php
	//útil para gerar nomes de arquivos...
	function removerCaracteresEspeciais($texto){
		return preg_replace(['/[\\\<\>\:\"\/\|\?\*\']/i','/\./'],['','-'], $texto);
	}
?>